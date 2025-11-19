<?php
session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Handle JSON input
$input = json_decode(file_get_contents('php://input'), true);
if ($input) {
    $action = $input['action'] ?? $action;
}

switch ($action) {
    case 'get_all':
        getAllPatients();
        break;
    case 'get_or_create':
        getOrCreatePatient($input);
        break;
    case 'create':
        createPatient($input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getAllPatients() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("
            SELECT id, name, email, phone, created_at
            FROM users 
            WHERE role = 'patient'
            ORDER BY name ASC
        ");
        
        $patients = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'patients' => $patients
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function getOrCreatePatient($data) {
    try {
        $pdo = getDBConnection();
        
        // First try to find existing patient by name
        $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ? AND role = 'patient'");
        $stmt->execute([$data['name']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo json_encode([
                'success' => true,
                'patient_id' => $existing['id'],
                'message' => 'Existing patient found'
            ]);
            return;
        }
        
        // Create new patient
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, role, password, created_at)
            VALUES (?, ?, 'patient', ?, NOW())
        ");
        
        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash('password', PASSWORD_DEFAULT)
        ]);
        
        $patientId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'patient_id' => $patientId,
            'message' => 'New patient created'
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function createPatient($data) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, role, password, created_at)
            VALUES (?, ?, ?, 'patient', ?, NOW())
        ");
        
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            password_hash('password', PASSWORD_DEFAULT)
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Patient created successfully',
            'patient_id' => $pdo->lastInsertId()
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
