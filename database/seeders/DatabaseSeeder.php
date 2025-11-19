<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\DoctorProfile;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@medicare.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        // Create Departments
        $departments = [
            ['name' => 'General Medicine', 'description' => 'General medical consultations and primary care'],
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular system care'],
            ['name' => 'Neurology', 'description' => 'Brain and nervous system disorders'],
            ['name' => 'Orthopedics', 'description' => 'Bone, joint, and muscle care'],
            ['name' => 'Pediatrics', 'description' => 'Medical care for children'],
            ['name' => 'Emergency', 'description' => 'Emergency medical services'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create Sample Doctors
        $doctors = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@medicare.com',
                'specialization' => 'General Medicine',
                'department' => 'General Medicine',
                'experience' => 10,
                'fee' => 100.00,
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@medicare.com',
                'specialization' => 'Cardiology',
                'department' => 'Cardiology',
                'experience' => 15,
                'fee' => 200.00,
            ],
            [
                'name' => 'Dr. Michael Brown',
                'email' => 'michael.brown@medicare.com',
                'specialization' => 'Neurology',
                'department' => 'Neurology',
                'experience' => 12,
                'fee' => 250.00,
            ],
        ];

        foreach ($doctors as $doctorData) {
            $department = Department::where('name', $doctorData['department'])->first();
            
            $doctor = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '+1' . rand(1000000000, 9999999999),
                'is_active' => true,
            ]);

            DoctorProfile::create([
                'user_id' => $doctor->id,
                'department_id' => $department->id,
                'license_number' => 'LIC' . rand(100000, 999999),
                'specialization' => $doctorData['specialization'],
                'experience_years' => $doctorData['experience'],
                'qualifications' => 'MBBS, MD',
                'consultation_fee' => $doctorData['fee'],
                'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'start_time' => '09:00',
                'end_time' => '17:00',
                'slot_duration' => 30,
                'is_available' => true,
            ]);
        }

        // Create Sample Patient
        User::create([
            'name' => 'John Doe',
            'email' => 'patient@medicare.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+1234567891',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'address' => '123 Main St, City, State',
            'blood_group' => 'O+',
            'is_active' => true,
        ]);

        // Create Sample Medicines
        $medicines = [
            [
                'name' => 'Paracetamol',
                'generic_name' => 'Acetaminophen',
                'brand' => 'Tylenol',
                'category' => 'Pain Relief',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'unit_price' => 0.50,
                'stock_quantity' => 1000,
                'minimum_stock_level' => 100,
                'expiry_date' => now()->addYears(2),
                'manufacturer' => 'PharmaCorp',
                'requires_prescription' => false,
            ],
            [
                'name' => 'Amoxicillin',
                'generic_name' => 'Amoxicillin',
                'brand' => 'Amoxil',
                'category' => 'Antibiotic',
                'dosage_form' => 'Capsule',
                'strength' => '250mg',
                'unit_price' => 2.00,
                'stock_quantity' => 500,
                'minimum_stock_level' => 50,
                'expiry_date' => now()->addYears(1),
                'manufacturer' => 'MediCorp',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Lisinopril',
                'generic_name' => 'Lisinopril',
                'brand' => 'Prinivil',
                'category' => 'Blood Pressure',
                'dosage_form' => 'Tablet',
                'strength' => '10mg',
                'unit_price' => 1.50,
                'stock_quantity' => 300,
                'minimum_stock_level' => 30,
                'expiry_date' => now()->addYears(1),
                'manufacturer' => 'CardioMed',
                'requires_prescription' => true,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: admin@medicare.com / password');
        $this->command->info('Doctor: john.smith@medicare.com / password');
        $this->command->info('Patient: patient@medicare.com / password');
    }
}