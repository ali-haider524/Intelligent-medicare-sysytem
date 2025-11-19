-- ============================================
-- Intelligent Medicare System - Database Setup
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS intelligent_medicare;
USE intelligent_medicare;

-- Users Table
CREATE TABLE users (
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
);

-- Departments Table
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    head_doctor_id VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Doctor Profiles Table
CREATE TABLE doctor_profiles (
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
);

-- Appointments Table
CREATE TABLE appointments (
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
);

-- Medicines Table
CREATE TABLE medicines (
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
);

-- Medical Records Table
CREATE TABLE medical_records (
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
);

-- Prescriptions Table
CREATE TABLE prescriptions (
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
);

-- AI Chat Sessions Table
CREATE TABLE ai_chat_sessions (
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
);

-- Inventory Alerts Table
CREATE TABLE inventory_alerts (
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
);

-- Billing Table
CREATE TABLE billing (
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
);

-- ============================================
-- Insert Sample Data
-- ============================================

-- Insert Super Admin
INSERT INTO users (name, email, password, role, phone, is_active) VALUES
('Super Admin', 'admin@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', '+1234567890', TRUE);
-- Password: password

-- Insert Departments
INSERT INTO departments (name, description, is_active) VALUES
('General Medicine', 'General medical consultations and primary care', TRUE),
('Cardiology', 'Heart and cardiovascular system care', TRUE),
('Neurology', 'Brain and nervous system disorders', TRUE),
('Orthopedics', 'Bone, joint, and muscle care', TRUE),
('Pediatrics', 'Medical care for children', TRUE),
('Emergency', 'Emergency medical services', TRUE);

-- Insert Doctors
INSERT INTO users (name, email, password, role, phone, is_active) VALUES
('Dr. John Smith', 'john.smith@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567891', TRUE),
('Dr. Sarah Johnson', 'sarah.johnson@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567892', TRUE),
('Dr. Michael Brown', 'michael.brown@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', '+11234567893', TRUE);
-- Password: password

-- Insert Doctor Profiles
INSERT INTO doctor_profiles (user_id, department_id, license_number, specialization, experience_years, qualifications, consultation_fee, available_days, start_time, end_time, slot_duration, is_available) VALUES
(2, 1, 'LIC123456', 'General Medicine', 10, 'MBBS, MD', 100.00, '["monday", "tuesday", "wednesday", "thursday", "friday"]', '09:00:00', '17:00:00', 30, TRUE),
(3, 2, 'LIC234567', 'Cardiology', 15, 'MBBS, MD, DM Cardiology', 200.00, '["monday", "tuesday", "wednesday", "thursday", "friday"]', '09:00:00', '17:00:00', 30, TRUE),
(4, 3, 'LIC345678', 'Neurology', 12, 'MBBS, MD, DM Neurology', 250.00, '["monday", "tuesday", "wednesday", "thursday", "friday"]', '09:00:00', '17:00:00', 30, TRUE);

-- Insert Sample Patient
INSERT INTO users (name, email, password, role, phone, date_of_birth, gender, address, blood_group, is_active) VALUES
('John Doe', 'patient@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient', '+11234567894', '1990-01-01', 'male', '123 Main St, City, State', 'O+', TRUE);
-- Password: password

-- Insert Sample Medicines
INSERT INTO medicines (name, generic_name, brand, category, dosage_form, strength, unit_price, stock_quantity, minimum_stock_level, expiry_date, manufacturer, requires_prescription, is_active) VALUES
('Paracetamol', 'Acetaminophen', 'Tylenol', 'Pain Relief', 'Tablet', '500mg', 0.50, 1000, 100, '2026-12-31', 'PharmaCorp', FALSE, TRUE),
('Amoxicillin', 'Amoxicillin', 'Amoxil', 'Antibiotic', 'Capsule', '250mg', 2.00, 500, 50, '2025-12-31', 'MediCorp', TRUE, TRUE),
('Lisinopril', 'Lisinopril', 'Prinivil', 'Blood Pressure', 'Tablet', '10mg', 1.50, 300, 30, '2025-12-31', 'CardioMed', TRUE, TRUE);

-- ============================================
-- Success Message
-- ============================================
SELECT 'Database setup completed successfully!' AS Status;
SELECT 'You can now login with these credentials:' AS Info;
SELECT 'Super Admin: admin@medicare.com / password' AS Credentials;
SELECT 'Doctor: john.smith@medicare.com / password' AS Credentials;
SELECT 'Patient: patient@medicare.com / password' AS Credentials;