<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

requireLogin();

if (!in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get total patients
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'patient'");
    $totalPatients = $stmt->fetch()['count'];
    
    // Get total doctors
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'doctor'");
    $totalDoctors = $stmt->fetch()['count'];
    
    // Get total nurses
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'nurse'");
    $totalNurses = $stmt->fetch()['count'];
    
    // Get total admins
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role IN ('admin', 'super_admin')");
    $totalAdmins = $stmt->fetch()['count'];
    
    // Get today's appointments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM appointments WHERE DATE(appointment_date) = CURDATE()");
    $todayAppointments = $stmt->fetch()['count'];
    
    // Get today's revenue (mock data for now)
    $todayRevenue = 12450;
    
    // Get low stock count (check if medicines table exists and has quantity column)
    $lowStockCount = 0;
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'medicines'");
        if ($stmt->fetch()) {
            // Check if quantity column exists
            $stmt = $pdo->query("SHOW COLUMNS FROM medicines LIKE 'quantity'");
            if ($stmt->fetch()) {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM medicines WHERE quantity < 100");
                $lowStockCount = $stmt->fetch()['count'];
            }
        }
    } catch (Exception $e) {
        // If medicines table doesn't exist or has issues, just use 0
        $lowStockCount = 0;
    }
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalPatients' => (int)$totalPatients,
            'totalDoctors' => (int)$totalDoctors,
            'todayAppointments' => (int)$todayAppointments,
            'todayRevenue' => $todayRevenue
        ],
        'roleCounts' => [
            'doctor' => (int)$totalDoctors,
            'patient' => (int)$totalPatients,
            'nurse' => (int)$totalNurses,
            'admin' => (int)$totalAdmins
        ],
        'lowStockCount' => (int)$lowStockCount
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading stats: ' . $e->getMessage()
    ]);
}
