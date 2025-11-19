<?php
session_start();
header('Content-Type: application/json');

$db_config = [
    'host' => 'localhost',
    'dbname' => 'intelligent_medicare',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']}", 
        $db_config['username'], 
        $db_config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_all':
        $stmt = $pdo->query("
            SELECT u.id, u.name, u.email, u.phone,
                   dp.specialization, dp.consultation_fee as fee, dp.experience_years,
                   d.name as department
            FROM users u
            JOIN doctor_profiles dp ON u.id = dp.user_id
            JOIN departments d ON dp.department_id = d.id
            WHERE u.role = 'doctor' AND u.is_active = 1 AND dp.is_available = 1
            ORDER BY u.name ASC
        ");
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'doctors' => $doctors]);
        break;
        
    case 'get_by_department':
        $deptId = $_GET['department_id'] ?? null;
        if (!$deptId) {
            echo json_encode(['success' => false, 'error' => 'Department ID required']);
            exit;
        }
        
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, dp.specialization, dp.consultation_fee as fee
            FROM users u
            JOIN doctor_profiles dp ON u.id = dp.user_id
            WHERE dp.department_id = ? AND u.is_active = 1 AND dp.is_available = 1
        ");
        $stmt->execute([$deptId]);
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'doctors' => $doctors]);
        break;
        
    case 'get_available_slots':
        $doctorId = $_GET['doctor_id'] ?? null;
        $date = $_GET['date'] ?? null;
        
        if (!$doctorId || !$date) {
            echo json_encode(['success' => false, 'error' => 'Doctor ID and date required']);
            exit;
        }
        
        // Get doctor's schedule
        $stmt = $pdo->prepare("SELECT * FROM doctor_profiles WHERE user_id = ?");
        $stmt->execute([$doctorId]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Generate time slots
        $slots = [];
        $start = strtotime($doctor['start_time']);
        $end = strtotime($doctor['end_time']);
        $duration = $doctor['slot_duration'] * 60; // Convert to seconds
        
        for ($time = $start; $time < $end; $time += $duration) {
            $slots[] = date('H:i:00', $time);
        }
        
        // Get booked slots
        $stmt = $pdo->prepare("
            SELECT TIME(appointment_date) as time_slot
            FROM appointments
            WHERE doctor_id = ? AND DATE(appointment_date) = ?
            AND status IN ('scheduled', 'confirmed')
        ");
        $stmt->execute([$doctorId, $date]);
        $booked = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Remove booked slots
        $available = array_diff($slots, $booked);
        
        echo json_encode(['success' => true, 'slots' => array_values($available)]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>