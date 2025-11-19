<?php
session_start();

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'intelligent_medicare',
    'username' => 'root',
    'password' => ''
];

$error = null;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $pdo = new PDO(
            "mysql:host={$db_config['host']};dbname={$db_config['dbname']}", 
            $db_config['username'], 
            $db_config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } catch (PDOException $e) {
        $error = "Database connection error. Please make sure the database is set up.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Intelligent Medicare System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Access your healthcare dashboard
                </p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required 
                               class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Password">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Demo Credentials</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-2">Test Accounts</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div><strong>Super Admin:</strong> admin@medicare.com / password</div>
                            <div><strong>Doctor:</strong> john.smith@medicare.com / password</div>
                            <div><strong>Patient:</strong> patient@medicare.com / password</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center space-y-2">
                <a href="welcome.php" class="block text-blue-600 hover:text-blue-800">
                    ← Back to Home
                </a>
                <a href="../demo.html" class="block text-gray-600 hover:text-gray-800 text-sm">
                    View Demo (No Login Required)
                </a>
            </div>
        </div>
    </div>
</body>
</html>