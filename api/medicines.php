<?php
session_start();
header('Content-Type: application/json');

// Database configuration
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
        $stmt = $pdo->query("SELECT * FROM medicines WHERE is_active = 1 ORDER BY name ASC");
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'medicines' => $medicines]);
        break;
        
    case 'get_low_stock':
        $stmt = $pdo->query("
            SELECT * FROM medicines 
            WHERE stock_quantity <= minimum_stock_level AND is_active = 1
            ORDER BY stock_quantity ASC
        ");
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'medicines' => $medicines]);
        break;
        
    case 'get_expiring':
        $stmt = $pdo->query("
            SELECT * FROM medicines 
            WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND is_active = 1
            ORDER BY expiry_date ASC
        ");
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'medicines' => $medicines]);
        break;
        
    case 'search':
        $query = $_GET['query'] ?? '';
        $stmt = $pdo->prepare("
            SELECT * FROM medicines 
            WHERE (name LIKE ? OR generic_name LIKE ? OR brand LIKE ?) AND is_active = 1
            ORDER BY name ASC
            LIMIT 20
        ");
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'medicines' => $medicines]);
        break;
        
    case 'update_stock':
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
            echo json_encode(['success' => false, 'error' => 'Not authorized']);
            exit;
        }
        
        $medicineId = $_POST['medicine_id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $operation = $_POST['operation'] ?? 'add'; // add or subtract
        
        if (!$medicineId || $quantity === null) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit;
        }
        
        if ($operation === 'add') {
            $stmt = $pdo->prepare("UPDATE medicines SET stock_quantity = stock_quantity + ? WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE medicines SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE id = ?");
        }
        
        $stmt->execute([$quantity, $medicineId]);
        
        // Check for low stock alert
        $stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ?");
        $stmt->execute([$medicineId]);
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($medicine['stock_quantity'] <= $medicine['minimum_stock_level']) {
            // Create alert
            $stmt = $pdo->prepare("
                INSERT INTO inventory_alerts (medicine_id, alert_type, message)
                VALUES (?, 'low_stock', ?)
            ");
            $message = "Medicine '{$medicine['name']}' is running low (Current: {$medicine['stock_quantity']})";
            $stmt->execute([$medicineId, $message]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Stock updated successfully']);
        break;
        
    case 'add':
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
            echo json_encode(['success' => false, 'error' => 'Not authorized']);
            exit;
        }
        
        $data = $_POST;
        $stmt = $pdo->prepare("
            INSERT INTO medicines 
            (name, generic_name, brand, category, dosage_form, strength, unit_price, stock_quantity, minimum_stock_level, expiry_date, manufacturer, requires_prescription)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['name'],
            $data['generic_name'] ?? null,
            $data['brand'],
            $data['category'],
            $data['dosage_form'],
            $data['strength'],
            $data['unit_price'],
            $data['stock_quantity'],
            $data['minimum_stock_level'] ?? 10,
            $data['expiry_date'],
            $data['manufacturer'],
            $data['requires_prescription'] ?? 1
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Medicine added successfully']);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>