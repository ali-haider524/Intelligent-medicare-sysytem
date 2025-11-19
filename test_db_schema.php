<?php
require_once 'config.php';

try {
    $pdo = getDBConnection();
    
    echo "=== APPOINTMENTS TABLE SCHEMA ===\n\n";
    $stmt = $pdo->query("DESCRIBE appointments");
    while($row = $stmt->fetch()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
