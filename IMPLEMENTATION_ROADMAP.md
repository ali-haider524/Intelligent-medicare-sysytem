# Implementation Roadmap - Missing Features

## Quick Reference: What You Have vs What's Specified

### ✅ **YOU HAVE (Working Now):**

- Professional clinic management system
- Multi-role authentication (Patient/Doctor/Admin)
- Appointment booking (basic)
- Medicine inventory with low stock alerts
- Modern admin panel with charts
- Doctor dashboard with patient queue
- MySQL database with all tables
- AI chatbot UI (basic responses)

### ❌ **YOU DON'T HAVE (From Spec):**

- Ollama AI integration
- AI triage with emergency detection
- Transactional booking numbers
- PostgreSQL (using MySQL instead)
- Redis queues (configured but not used)
- WebSockets for realtime
- PDF reports
- Batch tracking for medicines

---

## Phase 1: Critical AI Features (2-3 days)

### 1.1 Install Ollama (30 minutes)

```bash
# Windows
# Download from: https://ollama.ai/download
# Install and run:
ollama pull llama3.1
ollama serve
```

### 1.2 Create AI Service (2 hours)

**File:** `app/Services/OllamaService.php`

```php
<?php
namespace App\Services;

class OllamaService {
    private $baseUrl = 'http://localhost:11434/api/generate';

    public function chat($message) {
        $data = [
            'model' => 'llama3.1',
            'prompt' => $message,
            'stream' => false
        ];

        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function triage($symptoms) {
        $prompt = "You are a medical triage assistant. Analyze these symptoms and respond ONLY with valid JSON in this exact format:
{
  \"risk_level\": \"low|medium|high|emergency\",
  \"department\": \"General|Cardiology|Neurology|Emergency\",
  \"advice\": \"brief advice here\",
  \"should_book\": true|false,
  \"emergency_alert\": \"message if emergency, empty otherwise\"
}

Symptoms: $symptoms

RED FLAGS (mark as emergency):
- Chest pain + breathlessness + sweating
- Sudden severe headache, confusion, slurred speech
- Severe bleeding that won't stop
- Difficulty breathing, throat swelling
- Loss of consciousness

Respond with JSON only:";

        $response = $this->chat($prompt);
        return json_decode($response['response'], true);
    }
}
```

### 1.3 Update AI Chat API (1 hour)

**File:** `api/ai_chat.php`

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Services/OllamaService.php';

header('Content-Type: application/json');

$message = $_POST['message'] ?? '';

if (empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Message required']);
    exit;
}

$ollama = new \App\Services\OllamaService();

// Check if it's a symptom description
if (preg_match('/pain|hurt|sick|fever|cough|headache/i', $message)) {
    $triage = $ollama->triage($message);

    if ($triage['risk_level'] === 'emergency') {
        echo json_encode([
            'success' => true,
            'type' => 'emergency',
            'message' => '🚨 EMERGENCY: ' . $triage['emergency_alert'] . ' Call 911 or go to ER immediately!',
            'data' => $triage
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'type' => 'triage',
            'message' => $triage['advice'],
            'data' => $triage
        ]);
    }
} else {
    // General chat
    $response = $ollama->chat($message);
    echo json_encode([
        'success' => true,
        'type' => 'chat',
        'message' => $response['response']
    ]);
}
```

---

## Phase 2: Transactional Booking System (1 day)

### 2.1 Add Booking Number Column

```sql
ALTER TABLE appointments
ADD COLUMN booking_no INT UNSIGNED NOT NULL DEFAULT 0,
ADD COLUMN booking_date DATE NOT NULL,
ADD UNIQUE KEY unique_booking (booking_date, booking_no);
```

### 2.2 Create Booking Service (3 hours)

**File:** `app/Services/BookingService.php`

```php
<?php
namespace App\Services;

class BookingService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createAppointment($patientId, $doctorId, $date, $time) {
        $this->pdo->beginTransaction();

        try {
            // Get next booking number for the day (with lock)
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(MAX(booking_no), 0) + 1 as next_no
                FROM appointments
                WHERE booking_date = ?
                FOR UPDATE
            ");
            $stmt->execute([$date]);
            $bookingNo = $stmt->fetch()['next_no'];

            // Insert appointment
            $stmt = $this->pdo->prepare("
                INSERT INTO appointments
                (patient_id, doctor_id, appointment_date, appointment_time,
                 booking_date, booking_no, status)
                VALUES (?, ?, ?, ?, ?, ?, 'scheduled')
            ");
            $stmt->execute([
                $patientId, $doctorId, $date, $time,
                $date, $bookingNo
            ]);

            $appointmentId = $this->pdo->lastInsertId();

            $this->pdo->commit();

            return [
                'success' => true,
                'appointment_id' => $appointmentId,
                'booking_no' => $bookingNo,
                'booking_date' => $date
            ];

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ];
        }
    }
}
```

---

## Phase 3: Stock-Aware Prescribing (4 hours)

### 3.1 Update Prescription API

**File:** `api/prescriptions.php`

```php
<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicineId = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];

    $pdo = getDBConnection();

    // Check stock availability
    $stmt = $pdo->prepare("
        SELECT name, quantity as stock
        FROM medicines
        WHERE id = ?
    ");
    $stmt->execute([$medicineId]);
    $medicine = $stmt->fetch();

    if (!$medicine) {
        echo json_encode([
            'success' => false,
            'message' => 'Medicine not found'
        ]);
        exit;
    }

    if ($medicine['stock'] < $quantity) {
        echo json_encode([
            'success' => false,
            'message' => "Insufficient stock! Available: {$medicine['stock']}, Requested: $quantity",
            'available_stock' => $medicine['stock']
        ]);
        exit;
    }

    // Create prescription and reduce stock
    $pdo->beginTransaction();
    try {
        // Insert prescription
        $stmt = $pdo->prepare("
            INSERT INTO prescriptions
            (appointment_id, medicine_id, quantity, dosage, instructions)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['appointment_id'],
            $medicineId,
            $quantity,
            $_POST['dosage'],
            $_POST['instructions']
        ]);

        // Reduce stock
        $stmt = $pdo->prepare("
            UPDATE medicines
            SET quantity = quantity - ?
            WHERE id = ?
        ");
        $stmt->execute([$quantity, $medicineId]);

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Prescription created and stock updated'
        ]);

    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
```

---

## Phase 4: PDF Reports (3 hours)

### 4.1 Install TCPDF

```bash
composer require tecnickcom/tcpdf
```

### 4.2 Create Report Generator

**File:** `app/Services/ReportService.php`

```php
<?php
namespace App\Services;

require_once(__DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php');

class ReportService {
    public function generateOPDReport($date) {
        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $html = '<h1>OPD Report - ' . $date . '</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>Patient</th><th>Doctor</th><th>Time</th><th>Status</th></tr>';

        // Fetch data
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT u1.name as patient, u2.name as doctor,
                   a.appointment_time, a.status
            FROM appointments a
            JOIN users u1 ON a.patient_id = u1.id
            JOIN users u2 ON a.doctor_id = u2.id
            WHERE DATE(a.appointment_date) = ?
        ");
        $stmt->execute([$date]);

        while ($row = $stmt->fetch()) {
            $html .= "<tr>
                <td>{$row['patient']}</td>
                <td>{$row['doctor']}</td>
                <td>{$row['appointment_time']}</td>
                <td>{$row['status']}</td>
            </tr>";
        }

        $html .= '</table>';

        $pdf->writeHTML($html);
        $pdf->Output('opd_report_' . $date . '.pdf', 'D');
    }
}
```

---

## Phase 5: Batch Tracking (2 hours)

### 5.1 Create Batches Table

```sql
CREATE TABLE medicine_batches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medicine_id INT NOT NULL,
    batch_number VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    manufacturing_date DATE,
    expiry_date DATE NOT NULL,
    supplier VARCHAR(100),
    cost_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id),
    UNIQUE KEY (medicine_id, batch_number)
);
```

### 5.2 Update Inventory Management

Add batch selection when prescribing (FIFO - First In First Out).

---

## Quick Win: Add These Now (30 minutes)

### Update .env

```env
# Add these lines
AI_DRIVER=ollama
OLLAMA_URL=http://localhost:11434/api/generate
OLLAMA_MODEL=llama3.1

# For emergency alerts
EMERGENCY_PHONE=911
EMERGENCY_EMAIL=emergency@hospital.com
```

### Create Emergency Detection Function

**File:** `app/Helpers/EmergencyDetector.php`

```php
<?php
namespace App\Helpers;

class EmergencyDetector {
    private static $redFlags = [
        'chest pain' => ['breathless', 'sweating', 'nausea'],
        'severe headache' => ['confusion', 'slurred speech', 'vision'],
        'bleeding' => ['severe', 'won\'t stop', 'heavy'],
        'breathing' => ['difficulty', 'can\'t breathe', 'choking'],
        'unconscious' => ['passed out', 'fainted', 'collapsed']
    ];

    public static function check($symptoms) {
        $symptoms = strtolower($symptoms);

        foreach (self::$redFlags as $primary => $secondary) {
            if (strpos($symptoms, $primary) !== false) {
                foreach ($secondary as $sign) {
                    if (strpos($symptoms, $sign) !== false) {
                        return [
                            'is_emergency' => true,
                            'reason' => "Detected: $primary with $sign",
                            'action' => 'Call 911 immediately'
                        ];
                    }
                }
            }
        }

        return ['is_emergency' => false];
    }
}
```

---

## Summary: Implementation Time

| Feature                 | Time         | Priority     |
| ----------------------- | ------------ | ------------ |
| Ollama Integration      | 3 hours      | 🔴 Critical  |
| AI Triage               | 2 hours      | 🔴 Critical  |
| Booking Numbers         | 4 hours      | 🔴 Critical  |
| Stock-Aware Prescribing | 4 hours      | 🟡 High      |
| PDF Reports             | 3 hours      | 🟡 High      |
| Batch Tracking          | 2 hours      | 🟢 Medium    |
| **Total**               | **18 hours** | **2-3 days** |

---

## Decision Point

### Option A: Keep Current System (Recommended for Demo)

- ✅ Already 60% complete
- ✅ Professional UI
- ✅ All basic features working
- ✅ Good for final year project
- ⚠️ Add basic emergency detection (30 min)
- ⚠️ Add PDF reports (3 hours)

### Option B: Full Specification Implementation

- ⏱️ Requires 2-3 days additional work
- 🔧 Install Ollama
- 🔧 Implement all missing features
- 🎯 Production-ready system

**My Recommendation:** Go with Option A for your demo, then add features incrementally if needed.

---

**Next Steps:**

1. Review PROJECT_STATUS_COMPARISON.md
2. Decide which features you need
3. Follow this roadmap for implementation
4. Test thoroughly before demo

Your current system is solid and demo-ready! 🎉
