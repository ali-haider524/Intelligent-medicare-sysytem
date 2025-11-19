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

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? null;

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email already registered']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role, date_of_birth, gender, is_active)
            VALUES (?, ?, ?, ?, 'patient', ?, ?, 1)
        ");
        
        $stmt->execute([$name, $email, $phone, $hashedPassword, $dob, $gender]);

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Please login to continue.'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Registration failed. Please try again.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>