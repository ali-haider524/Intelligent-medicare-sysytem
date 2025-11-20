<?php
require_once 'config.php';

try {
    $pdo = getDBConnection();
    
    echo "=== CHECKING APPOINTMENTS ===\n\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM appointments");
    $result = $stmt->fetch();
    echo "Total appointments in database: " . $result['total'] . "\n\n";
    
    if ($result['total'] > 0) {
        echo "Recent appointments:\n";
        $stmt = $pdo->query("
            SELECT a.id, a.patient_id, a.doctor_id, a.appointment_date, a.status, a.created_at,
                   u.name as patient_name
            FROM appointments a
            LEFT JOIN users u ON a.patient_id = u.id
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        
        while ($row = $stmt->fetch()) {
            echo "ID: {$row['id']} | Patient: {$row['patient_name']} | Date: {$row['appointment_date']} | Status: {$row['status']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
