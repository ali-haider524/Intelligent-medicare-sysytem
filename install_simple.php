<?php
/**
 * Simple Database Installation Script
 * This version executes SQL statements one by one
 */

$success = false;
$error = null;
$message = '';
$steps = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_user = $_POST['db_user'] ?? 'root';
    $db_pass = $_POST['db_pass'] ?? '';
    $db_name = 'intelligent_medicare';

    try {
        // Step 1: Connect to MySQL
        $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        $steps[] = "✓ Connected to MySQL server";

        // Step 2: Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $db_name");
        $steps[] = "✓ Created database: $db_name";
        
        $pdo->exec("USE $db_name");
        $steps[] = "✓ Selected database";

        // Step 3: Create tables
        $pdo->exec("DROP TABLE IF EXISTS prescriptions");
        $pdo->exec("DROP TABLE IF EXISTS medical_records");
        $pdo->exec("DROP TABLE IF EXISTS billing");
        $pdo->exec("DROP TABLE IF EXISTS inventory_alerts");
        $pdo->exec("DROP TABLE IF EXISTS ai_chat_sessions");
        $pdo->exec("DROP TABLE IF EXISTS appointments");
        $pdo->exec("DROP TABLE IF EXISTS doctor_profiles");
        $pdo->exec("DROP TABLE IF EXISTS medicines");
        $pdo->exec("DROP TABLE IF EXISTS departments");
        $pdo->exec("DROP TABLE IF EXISTS users");
        $steps[] = "✓ Cleaned up existing tables";

        // Create users table
        $pdo->exec("CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('patient', 'doctor', 'admin', 'super_admin') DEFAULT 'patient',
            phone VARCHAR(255) NULL,
            date_of_birth DATE NULL,
            gender ENUM('male', 'female', 'other') NULL,
            address TEXT NULL,
            emergency_contact VARCHAR(255) NULL,
            blood_group VARCHAR(255) NULL,
            allergies TEXT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        $steps[] = "✓ Created users table";

        // Create departments table
        $pdo->exec("CREATE TABLE departments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NULL,
            head_doctor_id VARCHAR(255) NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        $steps[] = "✓ Created departments table";

        // Create doctor_profiles table
        $pdo->exec("CREATE TABLE doctor_profiles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            department_id BIGINT UNSIGNED NOT NULL,
            license_number VARCHAR(255) UNIQUE NOT NULL,
            specialization VARCHAR(255) NOT NULL,
            experience_years INT NOT NULL,
            qualifications TEXT NOT NULL,
            consultation_fee DECIMAL(8, 2) NOT NULL,
            available_days JSON NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            slot_duration INT DEFAULT 30,
            is_available BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
        )");
        $steps[] = "✓ Created doctor_profiles table";

        // Create appointments table
        $pdo->exec("CREATE TABLE appointments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            patient_id BIGINT UNSIGNED NOT NULL,
            doctor_id BIGINT UNSIGNED NOT NULL,
            department_id BIGINT UNSIGNED NOT NULL,
            appointment_date DATETIME NOT NULL,
            status ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
            booking_type ENUM('manual', 'ai_assisted', 'online') DEFAULT 'online',
            symptoms TEXT NULL,
            notes TEXT NULL,
            consultation_fee DECIMAL(8, 2) NULL,
            is_emergency BOOLEAN DEFAULT FALSE,
            booking_reference VARCHAR(255) UNIQUE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
        )");
        $steps[] = "✓ Created appointments table";

        // Create medicines table
        $pdo->exec("CREATE TABLE medicines (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            generic_name VARCHAR(255) NULL,
            brand VARCHAR(255) NOT NULL,
            category VARCHAR(255) NOT NULL,
            description TEXT NULL,
            dosage_form VARCHAR(255) NOT NULL,
            strength VARCHAR(255) NOT NULL,
            unit_price DECIMAL(8, 2) NOT NULL,
            stock_quantity INT NOT NULL,
            minimum_stock_level INT DEFAULT 10,
            expiry_date DATE NOT NULL,
            batch_number VARCHAR(255) NULL,
            manufacturer VARCHAR(255) NOT NULL,
            requires_prescription BOOLEAN DEFAULT TRUE,
            side_effects JSON NULL,
            contraindications JSON NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        $steps[] = "✓ Created medicines table";

        // Create medical_records table
        $pdo->exec("CREATE TABLE medical_records (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            patient_id BIGINT UNSIGNED NOT NULL,
            doctor_id BIGINT UNSIGNED NOT NULL,
            appointment_id BIGINT UNSIGNED NOT NULL,
            chief_complaint TEXT NOT NULL,
            history_of_present_illness TEXT NULL,
            physical_examination TEXT NULL,
            diagnosis TEXT NOT NULL,
            treatment_plan TEXT NOT NULL,
            vital_signs JSON NULL,
            lab_results JSON NULL,
            notes TEXT NULL,
            follow_up_date DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
        )");
        $steps[] = "✓ Created medical_records table";

        // Create remaining tables
        $pdo->exec("CREATE TABLE prescriptions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            medical_record_id BIGINT UNSIGNED NOT NULL,
            medicine_id BIGINT UNSIGNED NOT NULL,
            dosage VARCHAR(255) NOT NULL,
            frequency VARCHAR(255) NOT NULL,
            duration_days INT NOT NULL,
            instructions TEXT NULL,
            quantity_prescribed INT NOT NULL,
            is_dispensed BOOLEAN DEFAULT FALSE,
            dispensed_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (medical_record_id) REFERENCES medical_records(id) ON DELETE CASCADE,
            FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
        )");
        
        $pdo->exec("CREATE TABLE ai_chat_sessions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NULL,
            session_id VARCHAR(255) UNIQUE NOT NULL,
            chat_type ENUM('symptom_checker', 'appointment_booking', 'emergency_help', 'general_inquiry') NOT NULL,
            conversation_history JSON NOT NULL,
            extracted_symptoms JSON NULL,
            ai_recommendations JSON NULL,
            appointment_created BOOLEAN DEFAULT FALSE,
            created_appointment_id BIGINT UNSIGNED NULL,
            status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (created_appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
        )");
        
        $pdo->exec("CREATE TABLE inventory_alerts (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            medicine_id BIGINT UNSIGNED NOT NULL,
            alert_type ENUM('low_stock', 'expiry_warning', 'out_of_stock') NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            is_resolved BOOLEAN DEFAULT FALSE,
            resolved_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
        )");
        
        $pdo->exec("CREATE TABLE billing (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            patient_id BIGINT UNSIGNED NOT NULL,
            appointment_id BIGINT UNSIGNED NOT NULL,
            invoice_number VARCHAR(255) UNIQUE NOT NULL,
            consultation_fee DECIMAL(8, 2) DEFAULT 0,
            medicine_cost DECIMAL(8, 2) DEFAULT 0,
            additional_charges DECIMAL(8, 2) DEFAULT 0,
            discount DECIMAL(8, 2) DEFAULT 0,
            total_amount DECIMAL(8, 2) NOT NULL,
            payment_status ENUM('pending', 'paid', 'partially_paid', 'cancelled') DEFAULT 'pending',
            payment_method ENUM('cash', 'card', 'online', 'insurance') NULL,
            payment_date DATETIME NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
        )");
        $steps[] = "✓ Created all remaining tables";

        // Insert sample data
        $pdo->exec("INSERT INTO users (name, email, password, role, phone, is_active) VALUES
            ('Super Admin', 'admin@medicare.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', '+1234567890', TRUE)");
        $steps[] = "✓ Created admin user";

        $pdo->exec("INSERT INTO departments (name, description, is_active) VALUES
            ('General Medicine', 'General medical consultations and primary care', TRUE),
            ('Cardiology', 'Heart and cardiovascular system care', TRUE),
            ('Neurology', 'Brain and nervous system disorders', TRUE),
            ('Orthopedics', 'Bone, joint, and muscle care', TRUE),
            ('Pediatrics', 'Medical care for children', TRUE),
            ('Emergency', 'Emergency medical services', TRUE)");
        $steps[] = "✓ Created departments";

        $pdo->exec("INSERT INTO users (name, email, password, role, phone, is_active) VALUES
            ('Dr. John Smith', 'john.smith@medicare.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567891', TRUE),
            ('Dr. Sarah Johnson', 'sarah.johnson@medicare.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567892', TRUE),
            ('Dr. Michael Brown', 'michael.brown@medicare.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567893', TRUE)");
        $steps[] = "✓ Created doctor users";

        $pdo->exec("INSERT INTO doctor_profiles (user_id, department_id, license_number, specialization, experience_years, qualifications, consultation_fee, available_days, start_time, end_time, slot_duration, is_available) VALUES
            (2, 1, 'LIC123456', 'General Medicine', 10, 'MBBS, MD', 100.00, '[\"monday\", \"tuesday\", \"wednesday\", \"thursday\", \"friday\"]', '09:00:00', '17:00:00', 30, TRUE),
            (3, 2, 'LIC234567', 'Cardiology', 15, 'MBBS, MD, DM Cardiology', 200.00, '[\"monday\", \"tuesday\", \"wednesday\", \"thursday\", \"friday\"]', '09:00:00', '17:00:00', 30, TRUE),
            (4, 3, 'LIC345678', 'Neurology', 12, 'MBBS, MD, DM Neurology', 250.00, '[\"monday\", \"tuesday\", \"wednesday\", \"thursday\", \"friday\"]', '09:00:00', '17:00:00', 30, TRUE)");
        $steps[] = "✓ Created doctor profiles";

        $pdo->exec("INSERT INTO users (name, email, password, role, phone, date_of_birth, gender, address, blood_group, is_active) VALUES
            ('John Doe', 'patient@medicare.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient', '+11234567894', '1990-01-01', 'male', '123 Main St, City, State', 'O+', TRUE)");
        $steps[] = "✓ Created patient user";

        $pdo->exec("INSERT INTO medicines (name, generic_name, brand, category, dosage_form, strength, unit_price, stock_quantity, minimum_stock_level, expiry_date, manufacturer, requires_prescription, is_active) VALUES
            ('Paracetamol', 'Acetaminophen', 'Tylenol', 'Pain Relief', 'Tablet', '500mg', 0.50, 1000, 100, '2026-12-31', 'PharmaCorp', FALSE, TRUE),
            ('Amoxicillin', 'Amoxicillin', 'Amoxil', 'Antibiotic', 'Capsule', '250mg', 2.00, 500, 50, '2025-12-31', 'MediCorp', TRUE, TRUE),
            ('Lisinopril', 'Lisinopril', 'Prinivil', 'Blood Pressure', 'Tablet', '10mg', 1.50, 300, 30, '2025-12-31', 'CardioMed', TRUE, TRUE)");
        $steps[] = "✓ Created sample medicines";

        $success = true;
        $message = "Database installed successfully! You can now login with the demo credentials.";

    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
        $steps[] = "✗ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Database Installation - Intelligent Medicare System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Simple Database Installation
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Intelligent Medicare System Setup
                </p>
            </div>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong>✅ Success!</strong><br>
                    <?= htmlspecialchars($message) ?>
                </div>

                <?php if (!empty($steps)): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Installation Steps:</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <?php foreach ($steps as $step): ?>
                                <div><?= htmlspecialchars($step) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-semibold text-blue-900 mb-3">Login Credentials:</h3>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div><strong>Super Admin:</strong><br>admin@medicare.com / password</div>
                        <div><strong>Doctor:</strong><br>john.smith@medicare.com / password</div>
                        <div><strong>Patient:</strong><br>patient@medicare.com / password</div>
                    </div>
                </div>

                <div class="text-center space-y-2">
                    <a href="index.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                        Go to Application
                    </a>
                    <a href="demo.html" class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold">
                        View Demo
                    </a>
                </div>

            <?php else: ?>
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    
                    <?php if (!empty($steps)): ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Progress:</h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <?php foreach ($steps as $step): ?>
                                    <div><?= htmlspecialchars($step) ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form class="mt-8 space-y-6" method="POST">
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label for="db_host" class="block text-sm font-medium text-gray-700 mb-1">
                                Database Host
                            </label>
                            <input id="db_host" name="db_host" type="text" value="localhost" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="db_user" class="block text-sm font-medium text-gray-700 mb-1">
                                Database Username
                            </label>
                            <input id="db_user" name="db_user" type="text" value="root" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="db_pass" class="block text-sm font-medium text-gray-700 mb-1">
                                Database Password
                            </label>
                            <input id="db_pass" name="db_pass" type="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Leave empty if no password">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">What will be installed:</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>✓ Database: intelligent_medicare</li>
                            <li>✓ 10 Tables (users, appointments, medicines, etc.)</li>
                            <li>✓ 3 Demo users (admin, doctor, patient)</li>
                            <li>✓ 6 Departments</li>
                            <li>✓ 3 Sample medicines</li>
                        </ul>
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Install Database
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="demo.html" class="text-blue-600 hover:text-blue-800 text-sm">
                        Skip and view demo instead →
                    </a>
                </div>
            <?php endif; ?>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-900 mb-2">⚠️ Important Notes:</h3>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Make sure XAMPP MySQL is running</li>
                    <li>• Default MySQL username is usually "root"</li>
                    <li>• Default password is usually empty</li>
                    <li>• This will create a new database</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>