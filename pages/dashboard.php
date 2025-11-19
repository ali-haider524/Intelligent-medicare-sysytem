<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Route to appropriate dashboard based on role
if ($user['role'] === 'patient') {
    include 'dashboard_patient.php';
} elseif ($user['role'] === 'doctor') {
    include 'dashboard_doctor.php';
} elseif (in_array($user['role'], ['admin', 'super_admin'])) {
    include 'dashboard_admin.php';
} else {
    die("Invalid user role");
}
?>