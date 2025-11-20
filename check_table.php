<?php
require_once 'config.php';
$pdo = getDBConnection();
$stmt = $pdo->query('DESCRIBE appointments');
while($row = $stmt->fetch()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}