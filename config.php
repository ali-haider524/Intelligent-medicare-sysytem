<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'intelligent_medicare');
define('DB_USER', 'root');
define('DB_PASS', '');

// Get Database Connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

// Check if user is logged in
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../pages/login.php');
        exit();
    }
}

// Get current logged in user
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

// Check if user has specific role
function hasRole($roles) {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    
    if (is_array($roles)) {
        return in_array($_SESSION['user_role'], $roles);
    }
    
    return $_SESSION['user_role'] === $roles;
}
