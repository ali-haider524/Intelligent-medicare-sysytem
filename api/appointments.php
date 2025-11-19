<?php
session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_all':
        getAllAppointments();
        break;
    case 'create':
        createAppointment();
        break;
    case 'update':
        updateAppointment();
        break;
    case 'delete':
        deleteAppointment();
        break;
    case 'get_by_status':
        getAppointmentsByStatus();
        break;
    case 'get_upcoming':
        getUpcomingAppointments();
        break;
    case 'get_today':
        getTodayAppointments();
        break;
    case 'book':
        bookAppointment();
        break;
    case 'update_status':
        updateAppointmentStatus();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getAllAppointments() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("
            SELECT a.*, 
                   u1.name as patient_name, u1.email as patient_email, u1.phone as patient_phone,
                   u2.name as doctor_name,
                   d.name as department_name
            FROM appointments a
            LEFT JOIN users u1 ON a.patient_id = u1.id
            LEFT JOIN users u2 ON a.doctor_id = u2.id
            LEFT JOIN departments d ON a.department_id = d.id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
        ");
        
        $appointments = $stmt->fetchAll();
        
        // If no appointments exist, create some sample data
        if (empty($appointments)) {
            createSampleAppointments($pdo);
            // Fetch again after creating sample data
            $stmt = $pdo->query("
                SELECT a.*, 
                       u1.name as patient_name, u1.email as patient_email, u1.phone as patient_phone,
                       u2.name as doctor_name,
                       d.name as department_name
                FROM appointments a
                LEFT JOIN users u1 ON a.patient_id = u1.id
                LEFT JOIN users u2 ON a.doctor_id = u2.id
                LEFT JOIN departments d ON a.department_id = d.id
                ORDER BY a.appointment_date DESC, a.appointment_time DESC
            ");
            $appointments = $stmt->fetchAll();
        }
        
        echo json_encode([
            'success' => true,
            'appointments' => $appointments
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function createAppointment() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'No data received']);
            return;
        }
        
        $pdo = getDBConnection();
        
        // Combine date and time into datetime
        $appointmentDateTime = $data['appointment_date'] . ' ' . $data['appointment_time'];
        
        // Generate booking reference
        $bookingRef = 'APT-' . strtoupper(substr(md5(uniqid()), 0, 8));
        
        $stmt = $pdo->prepare("
            INSERT INTO appointments 
            (patient_id, doctor_id, department_id, appointment_date, symptoms, notes, booking_reference, status, booking_type, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'scheduled', 'manual', NOW())
        ");
        
        $stmt->execute([
            $data['patient_id'],
            $data['doctor_id'],
            $data['department_id'] ?? 1,
            $appointmentDateTime,
            $data['reason'] ?? '',
            $data['notes'] ?? '',
            $bookingRef
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Appointment created successfully',
            'appointment_id' => $pdo->lastInsertId(),
            'booking_reference' => $bookingRef
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function getAppointmentsByStatus() {
    try {
        $status = $_GET['status'] ?? '';
        $pdo = getDBConnection();
        
        $sql = "
            SELECT a.*, 
                   u1.name as patient_name, u1.email as patient_email, u1.phone as patient_phone,
                   u2.name as doctor_name,
                   d.name as department_name
            FROM appointments a
            LEFT JOIN users u1 ON a.patient_id = u1.id
            LEFT JOIN users u2 ON a.doctor_id = u2.id
            LEFT JOIN departments d ON a.department_id = d.id
        ";
        
        if ($status && $status !== 'all') {
            $sql .= " WHERE a.status = :status";
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $stmt = $pdo->prepare($sql);
        if ($status && $status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        
        $appointments = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'appointments' => $appointments
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function createSampleAppointments($pdo) {
    try {
        // Get some users for sample data
        $patients = $pdo->query("SELECT id, name FROM users WHERE role = 'patient' LIMIT 5")->fetchAll();
        $doctors = $pdo->query("SELECT id, name FROM users WHERE role = 'doctor' LIMIT 3")->fetchAll();
        $departments = $pdo->query("SELECT id, name FROM departments LIMIT 3")->fetchAll();
        
        if (empty($patients) || empty($doctors)) {
            // Create sample users if they don't exist
            createSampleUsers($pdo);
            $patients = $pdo->query("SELECT id, name FROM users WHERE role = 'patient' LIMIT 5")->fetchAll();
            $doctors = $pdo->query("SELECT id, name FROM users WHERE role = 'doctor' LIMIT 3")->fetchAll();
        }
        
        if (empty($departments)) {
            createSampleDepartments($pdo);
            $departments = $pdo->query("SELECT id, name FROM departments LIMIT 3")->fetchAll();
        }
        
        $sampleAppointments = [
            [
                'patient_id' => $patients[0]['id'] ?? 1,
                'doctor_id' => $doctors[0]['id'] ?? 1,
                'department_id' => $departments[0]['id'] ?? 1,
                'appointment_date' => date('Y-m-d'),
                'appointment_time' => '10:00:00',
                'reason' => 'Regular checkup',
                'status' => 'scheduled'
            ],
            [
                'patient_id' => $patients[1]['id'] ?? 1,
                'doctor_id' => $doctors[1]['id'] ?? 1,
                'department_id' => $departments[1]['id'] ?? 1,
                'appointment_date' => date('Y-m-d'),
                'appointment_time' => '11:30:00',
                'reason' => 'Follow-up consultation',
                'status' => 'completed'
            ],
            [
                'patient_id' => $patients[2]['id'] ?? 1,
                'doctor_id' => $doctors[0]['id'] ?? 1,
                'department_id' => $departments[0]['id'] ?? 1,
                'appointment_date' => date('Y-m-d', strtotime('+1 day')),
                'appointment_time' => '14:00:00',
                'reason' => 'Consultation',
                'status' => 'scheduled'
            ],
            [
                'patient_id' => $patients[3]['id'] ?? 1,
                'doctor_id' => $doctors[2]['id'] ?? 1,
                'department_id' => $departments[2]['id'] ?? 1,
                'appointment_date' => date('Y-m-d', strtotime('-1 day')),
                'appointment_time' => '09:00:00',
                'reason' => 'Emergency consultation',
                'status' => 'cancelled'
            ],
            [
                'patient_id' => $patients[4]['id'] ?? 1,
                'doctor_id' => $doctors[1]['id'] ?? 1,
                'department_id' => $departments[1]['id'] ?? 1,
                'appointment_date' => date('Y-m-d'),
                'appointment_time' => '15:30:00',
                'reason' => 'Routine examination',
                'status' => 'scheduled'
            ]
        ];
        
        foreach ($sampleAppointments as $appointment) {
            $stmt = $pdo->prepare("
                INSERT INTO appointments (patient_id, doctor_id, department_id, appointment_date, appointment_time, reason, status, created_at)
                VALUES (:patient_id, :doctor_id, :department_id, :appointment_date, :appointment_time, :reason, :status, NOW())
            ");
            $stmt->execute($appointment);
        }
    } catch (Exception $e) {
        error_log("Error creating sample appointments: " . $e->getMessage());
    }
}

function createSampleUsers($pdo) {
    try {
        // Create sample patients
        $samplePatients = [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '123-456-7890'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'phone' => '123-456-7891'],
            ['name' => 'Mike Johnson', 'email' => 'mike.johnson@example.com', 'phone' => '123-456-7892'],
            ['name' => 'Sarah Wilson', 'email' => 'sarah.wilson@example.com', 'phone' => '123-456-7893'],
            ['name' => 'David Brown', 'email' => 'david.brown@example.com', 'phone' => '123-456-7894']
        ];
        
        foreach ($samplePatients as $patient) {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, phone, role, password, created_at)
                VALUES (:name, :email, :phone, 'patient', :password, NOW())
            ");
            $stmt->execute([
                'name' => $patient['name'],
                'email' => $patient['email'],
                'phone' => $patient['phone'],
                'password' => password_hash('password', PASSWORD_DEFAULT)
            ]);
        }
        
        // Create sample doctors
        $sampleDoctors = [
            ['name' => 'Dr. Smith', 'email' => 'dr.smith@medicare.com'],
            ['name' => 'Dr. Johnson', 'email' => 'dr.johnson@medicare.com'],
            ['name' => 'Dr. Brown', 'email' => 'dr.brown@medicare.com']
        ];
        
        foreach ($sampleDoctors as $doctor) {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, role, password, created_at)
                VALUES (:name, :email, 'doctor', :password, NOW())
            ");
            $stmt->execute([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => password_hash('password', PASSWORD_DEFAULT)
            ]);
        }
    } catch (Exception $e) {
        error_log("Error creating sample users: " . $e->getMessage());
    }
}

function createSampleDepartments($pdo) {
    try {
        $sampleDepartments = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular care'],
            ['name' => 'Neurology', 'description' => 'Brain and nervous system care'],
            ['name' => 'General Medicine', 'description' => 'General medical care']
        ];
        
        foreach ($sampleDepartments as $dept) {
            $stmt = $pdo->prepare("
                INSERT INTO departments (name, description, created_at)
                VALUES (:name, :description, NOW())
            ");
            $stmt->execute($dept);
        }
    } catch (Exception $e) {
        error_log("Error creating sample departments: " . $e->getMessage());
    }
}

function getUpcomingAppointments() {
    try {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $pdo = getDBConnection();
        
        if ($role === 'patient') {
            $stmt = $pdo->prepare("
                SELECT a.*, u.name as doctor_name, d.name as department_name 
                FROM appointments a
                JOIN users u ON a.doctor_id = u.id
                LEFT JOIN departments d ON a.department_id = d.id
                WHERE a.patient_id = ? AND a.appointment_date > NOW()
                ORDER BY a.appointment_date ASC
                LIMIT 5
            ");
            $stmt->execute([$userId]);
        } else if ($role === 'doctor') {
            $stmt = $pdo->prepare("
                SELECT a.*, u.name as patient_name, d.name as department_name 
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                LEFT JOIN departments d ON a.department_id = d.id
                WHERE a.doctor_id = ? AND a.appointment_date > NOW()
                ORDER BY a.appointment_date ASC
                LIMIT 5
            ");
            $stmt->execute([$userId]);
        } else {
            $stmt = $pdo->query("
                SELECT a.*, 
                    p.name as patient_name, 
                    d.name as doctor_name,
                    dept.name as department_name 
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                LEFT JOIN departments dept ON a.department_id = dept.id
                WHERE a.appointment_date > NOW()
                ORDER BY a.appointment_date ASC
                LIMIT 10
            ");
        }
        
        $appointments = $stmt->fetchAll();
        echo json_encode(['success' => true, 'appointments' => $appointments]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function getTodayAppointments() {
    try {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $pdo = getDBConnection();
        
        if ($role === 'doctor') {
            $stmt = $pdo->prepare("
                SELECT a.*, u.name as patient_name, u.phone
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                WHERE a.doctor_id = ? AND DATE(a.appointment_date) = CURDATE()
                ORDER BY a.appointment_time ASC
            ");
            $stmt->execute([$userId]);
        } else {
            $stmt = $pdo->query("
                SELECT a.*, 
                    p.name as patient_name, 
                    d.name as doctor_name,
                    dept.name as department_name 
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                LEFT JOIN departments dept ON a.department_id = dept.id
                WHERE DATE(a.appointment_date) = CURDATE()
                ORDER BY a.appointment_time ASC
            ");
        }
        
        $appointments = $stmt->fetchAll();
        echo json_encode(['success' => true, 'appointments' => $appointments]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function bookAppointment() {
    try {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            return;
        }
        
        $doctorId = $_POST['doctor_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;
        $symptoms = $_POST['symptoms'] ?? '';
        
        if (!$doctorId || !$date || !$time) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            return;
        }
        
        $pdo = getDBConnection();
        
        // Get doctor's department
        $stmt = $pdo->prepare("SELECT department_id FROM doctor_profiles WHERE user_id = ?");
        $stmt->execute([$doctorId]);
        $doctor = $stmt->fetch();
        
        // Create appointment
        $appointmentDate = $date . ' ' . $time;
        $bookingRef = 'APT-' . strtoupper(substr(md5(uniqid()), 0, 8));
        
        $stmt = $pdo->prepare("
            INSERT INTO appointments 
            (patient_id, doctor_id, department_id, appointment_date, reason, booking_reference, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'scheduled', NOW())
        ");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $doctorId,
            $doctor['department_id'] ?? null,
            $appointmentDate,
            $symptoms,
            $bookingRef
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Appointment booked successfully!',
            'booking_reference' => $bookingRef
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function updateAppointment() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            UPDATE appointments 
            SET status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        
        $stmt->execute([
            'status' => $data['status'],
            'id' => $data['id']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Appointment updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function updateAppointmentStatus() {
    try {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not authorized']);
            return;
        }
        
        $appointmentId = $_POST['appointment_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$appointmentId || !$status) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            return;
        }
        
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE appointments SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $appointmentId]);
        
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function deleteAppointment() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? $_GET['id'] ?? $_POST['id'];
        
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Appointment deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}