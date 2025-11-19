<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header('Location: ../pages/login.php');
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
} catch (PDOException $e) {
    die("Database error");
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Intelligent Medicare System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="patientDashboard()">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-600">🏥 Intelligent Medicare</h1>
                    <div class="ml-10 flex space-x-4">
                        <a href="#" @click.prevent="currentTab = 'dashboard'" 
                           :class="currentTab === 'dashboard' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="#" @click.prevent="currentTab = 'appointments'" 
                           :class="currentTab === 'appointments' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Appointments
                        </a>
                        <a href="#" @click.prevent="currentTab = 'book'" 
                           :class="currentTab === 'book' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Book Appointment
                        </a>
                        <a href="#" @click.prevent="currentTab = 'history'" 
                           :class="currentTab === 'history' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Medical History
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-4">👤 <?= htmlspecialchars($user['name']) ?></span>
                    <a href="?action=logout" class="text-sm text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Dashboard Tab -->
        <div x-show="currentTab === 'dashboard'">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Upcoming Appointments</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="upcomingCount">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Completed Visits</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="completedCount">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Medical Records</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="recordsCount">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Appointments</h3>
                </div>
                <div class="p-6">
                    <div x-show="loading" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    
                    <div x-show="!loading && upcomingAppointments.length === 0" class="text-center py-8 text-gray-500">
                        No upcoming appointments. Book one now!
                    </div>
                    
                    <div x-show="!loading && upcomingAppointments.length > 0" class="space-y-4">
                        <template x-for="apt in upcomingAppointments" :key="apt.id">
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900" x-text="'Dr. ' + apt.doctor_name"></h4>
                                        <p class="text-sm text-gray-600" x-text="apt.department_name"></p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <span x-text="formatDate(apt.appointment_date)"></span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">Ref: <span x-text="apt.booking_reference"></span></p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-blue-100 text-blue-800': apt.status === 'scheduled',
                                              'bg-green-100 text-green-800': apt.status === 'confirmed',
                                              'bg-yellow-100 text-yellow-800': apt.status === 'in_progress'
                                          }"
                                          x-text="apt.status.charAt(0).toUpperCase() + apt.status.slice(1)">
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Tab -->
        <div x-show="currentTab === 'appointments'">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Appointments</h2>
            
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div x-show="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    
                    <div x-show="!loading" class="space-y-4">
                        <template x-for="apt in allAppointments" :key="apt.id">
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="font-semibold text-gray-900" x-text="'Dr. ' + apt.doctor_name"></h4>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-blue-100 text-blue-800': apt.status === 'scheduled',
                                                      'bg-green-100 text-green-800': apt.status === 'confirmed',
                                                      'bg-yellow-100 text-yellow-800': apt.status === 'in_progress',
                                                      'bg-gray-100 text-gray-800': apt.status === 'completed',
                                                      'bg-red-100 text-red-800': apt.status === 'cancelled'
                                                  }"
                                                  x-text="apt.status.charAt(0).toUpperCase() + apt.status.slice(1)">
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1" x-text="apt.department_name"></p>
                                        <p class="text-sm text-gray-500 mt-1" x-text="formatDate(apt.appointment_date)"></p>
                                        <p class="text-sm text-gray-600 mt-2" x-show="apt.symptoms">
                                            <strong>Symptoms:</strong> <span x-text="apt.symptoms"></span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">Booking Ref: <span x-text="apt.booking_reference"></span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">$<span x-text="apt.consultation_fee"></span></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Appointment Tab -->
        <div x-show="currentTab === 'book'">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Book New Appointment</h2>
            
            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="bookAppointment()" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                        <select x-model="bookingForm.doctor_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Choose a doctor...</option>
                            <template x-for="doctor in doctors" :key="doctor.id">
                                <option :value="doctor.id" x-text="`Dr. ${doctor.name} - ${doctor.specialization} ($${doctor.fee})`"></option>
                            </template>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" x-model="bookingForm.date" required
                                   :min="new Date().toISOString().split('T')[0]"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                            <select x-model="bookingForm.time" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select time...</option>
                                <option value="09:00:00">09:00 AM</option>
                                <option value="10:00:00">10:00 AM</option>
                                <option value="11:00:00">11:00 AM</option>
                                <option value="14:00:00">02:00 PM</option>
                                <option value="15:00:00">03:00 PM</option>
                                <option value="16:00:00">04:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Symptoms / Reason for Visit</label>
                        <textarea x-model="bookingForm.symptoms" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe your symptoms..."></textarea>
                    </div>

                    <div x-show="bookingMessage" class="p-4 rounded-lg"
                         :class="bookingSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                         x-text="bookingMessage">
                    </div>

                    <button type="submit" :disabled="bookingLoading"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors disabled:opacity-50">
                        <span x-show="!bookingLoading">Book Appointment</span>
                        <span x-show="bookingLoading">Booking...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Medical History Tab -->
        <div x-show="currentTab === 'history'">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Medical History</h2>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Your medical records will appear here after doctor consultations.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Chat Widget -->
    <div class="fixed bottom-4 right-4 z-50">
        <button @click="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </button>
    </div>

    <script>
        function patientDashboard() {
            return {
                currentTab: 'dashboard',
                loading: false,
                upcomingAppointments: [],
                allAppointments: [],
                doctors: [],
                upcomingCount: 0,
                completedCount: 0,
                recordsCount: 0,
                bookingForm: {
                    doctor_id: '',
                    date: '',
                    time: '',
                    symptoms: ''
                },
                bookingLoading: false,
                bookingMessage: '',
                bookingSuccess: false,

                init() {
                    this.loadDashboardData();
                    this.loadDoctors();
                },

                async loadDashboardData() {
                    this.loading = true;
                    try {
                        const response = await fetch('../api/appointments.php?action=get_upcoming');
                        const data = await response.json();
                        if (data.success) {
                            this.upcomingAppointments = data.appointments;
                            this.upcomingCount = data.appointments.length;
                        }
                    } catch (error) {
                        console.error('Error loading data:', error);
                    }
                    this.loading = false;
                },

                async loadDoctors() {
                    try {
                        const response = await fetch('../api/doctors.php?action=get_all');
                        const data = await response.json();
                        if (data.success) {
                            this.doctors = data.doctors;
                        }
                    } catch (error) {
                        console.error('Error loading doctors:', error);
                    }
                },

                async bookAppointment() {
                    this.bookingLoading = true;
                    this.bookingMessage = '';
                    
                    try {
                        const formData = new FormData();
                        formData.append('action', 'book');
                        formData.append('doctor_id', this.bookingForm.doctor_id);
                        formData.append('date', this.bookingForm.date);
                        formData.append('time', this.bookingForm.time);
                        formData.append('symptoms', this.bookingForm.symptoms);

                        const response = await fetch('../api/appointments.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.bookingSuccess = true;
                            this.bookingMessage = data.message + ' Booking Reference: ' + data.booking_reference;
                            this.bookingForm = { doctor_id: '', date: '', time: '', symptoms: '' };
                            this.loadDashboardData();
                        } else {
                            this.bookingSuccess = false;
                            this.bookingMessage = data.error || 'Failed to book appointment';
                        }
                    } catch (error) {
                        this.bookingSuccess = false;
                        this.bookingMessage = 'Error booking appointment';
                    }
                    
                    this.bookingLoading = false;
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleString('en-US', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                toggleChat() {
                    // AI chat functionality
                    alert('AI Chat feature - Connect to your AI service here!');
                }
            }
        }
    </script>
</body>
</html>