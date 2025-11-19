<?php
require_once __DIR__ . '/../config.php';

requireLogin();

if (!in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
    die('Access denied');
}

$user = getCurrentUser();
$pdo = getDBConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Intelligent Medicare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { font-family: 'Inter', sans-serif; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .sidebar-item {
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #fff;
        }
        
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--tw-gradient-stops));
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="adminDashboard()" x-init="init()">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Modern Sidebar -->
        <aside class="w-64 gradient-bg text-white flex-shrink-0 overflow-y-auto shadow-xl">
            <!-- Logo Section -->
            <div class="p-4 border-b border-white border-opacity-20">
                <div class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-lg">
                        <i class="fas fa-hospital-alt text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold">Intelligent Medicare</h1>
                        <p class="text-xs text-white text-opacity-60">Clinic Management</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Summary -->
            <div class="p-3">
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-3">
                    <div class="text-center mb-2">
                        <p class="text-xs text-white text-opacity-60 mb-1">System Status</p>
                        <div class="flex items-center justify-center space-x-1.5">
                            <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium">All Systems Operational</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-white bg-opacity-10 rounded-lg p-2">
                            <p class="text-xl font-bold" x-text="stats.todayAppointments">0</p>
                            <p class="text-xs text-white text-opacity-60">Today</p>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-lg p-2">
                            <p class="text-xl font-bold" x-text="stats.lowStockCount">0</p>
                            <p class="text-xs text-white text-opacity-60">Alerts</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-3 space-y-0.5">
                <a href="#" @click.prevent="changePage('dashboard')" 
                   :class="currentPage === 'dashboard' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-chart-line w-4 text-sm"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#" @click.prevent="changePage('billing')" 
                   :class="currentPage === 'billing' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-file-invoice-dollar w-4 text-sm"></i>
                    <span>Billing</span>
                </a>

                <a href="#" @click.prevent="changePage('appointments')" 
                   :class="currentPage === 'appointments' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-calendar-check w-4 text-sm"></i>
                    <span>Appointment</span>
                    <span x-show="stats.todayAppointments > 0" 
                          class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full"
                          x-text="stats.todayAppointments"></span>
                </a>

                <a href="#" @click.prevent="changePage('opd')" 
                   :class="currentPage === 'opd' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-procedures w-4 text-sm"></i>
                    <span>OPD - Out Patient</span>
                </a>

                <a href="#" @click.prevent="changePage('ipd')" 
                   :class="currentPage === 'ipd' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-bed w-4 text-sm"></i>
                    <span>IPD - In Patient</span>
                </a>

                <a href="#" @click.prevent="changePage('pharmacy')" 
                   :class="currentPage === 'pharmacy' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-pills w-4 text-sm"></i>
                    <span>Pharmacy</span>
                    <span x-show="stats.lowStockCount > 0" 
                          class="ml-auto bg-yellow-500 text-white text-xs px-1.5 py-0.5 rounded-full pulse-animation"
                          x-text="stats.lowStockCount"></span>
                </a>

                <a href="#" @click.prevent="changePage('pathology')" 
                   :class="currentPage === 'pathology' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-microscope w-4 text-sm"></i>
                    <span>Pathology</span>
                </a>

                <a href="#" @click.prevent="changePage('radiology')" 
                   :class="currentPage === 'radiology' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-x-ray w-4 text-sm"></i>
                    <span>Radiology</span>
                </a>

                <a href="#" @click.prevent="changePage('blood-bank')" 
                   :class="currentPage === 'blood-bank' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-tint w-4 text-sm"></i>
                    <span>Blood Bank</span>
                </a>

                <a href="#" @click.prevent="changePage('ambulance')" 
                   :class="currentPage === 'ambulance' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-ambulance w-4 text-sm"></i>
                    <span>Ambulance</span>
                </a>

                <a href="#" @click.prevent="changePage('front-office')" 
                   :class="currentPage === 'front-office' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-desktop w-4 text-sm"></i>
                    <span>Front Office</span>
                </a>

                <a href="#" @click.prevent="changePage('birth-death')" 
                   :class="currentPage === 'birth-death' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-certificate w-4 text-sm"></i>
                    <span>Birth & Death Record</span>
                </a>

                <a href="#" @click.prevent="changePage('human-resource')" 
                   :class="currentPage === 'human-resource' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-users-cog w-4 text-sm"></i>
                    <span>Human Resource</span>
                </a>

                <a href="#" @click.prevent="changePage('duty-roster')" 
                   :class="currentPage === 'duty-roster' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-calendar-alt w-4 text-sm"></i>
                    <span>Duty Roster</span>
                </a>

                <a href="#" @click.prevent="changePage('annual-calendar')" 
                   :class="currentPage === 'annual-calendar' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-calendar-year w-4 text-sm"></i>
                    <span>Annual Calendar</span>
                </a>

                <a href="#" @click.prevent="changePage('referral')" 
                   :class="currentPage === 'referral' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-share-alt w-4 text-sm"></i>
                    <span>Referral</span>
                </a>

                <a href="#" @click.prevent="changePage('tpa-management')" 
                   :class="currentPage === 'tpa-management' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-handshake w-4 text-sm"></i>
                    <span>TPA Management</span>
                </a>

                <a href="#" @click.prevent="changePage('finance')" 
                   :class="currentPage === 'finance' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-money-bill-wave w-4 text-sm"></i>
                    <span>Finance</span>
                </a>

                <a href="#" @click.prevent="changePage('messaging')" 
                   :class="currentPage === 'messaging' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-comments w-4 text-sm"></i>
                    <span>Messaging</span>
                </a>

                <a href="#" @click.prevent="changePage('inventory')" 
                   :class="currentPage === 'inventory' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-boxes w-4 text-sm"></i>
                    <span>Inventory</span>
                </a>

                <a href="#" @click.prevent="changePage('download-center')" 
                   :class="currentPage === 'download-center' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-download w-4 text-sm"></i>
                    <span>Download Center</span>
                </a>

                <a href="#" @click.prevent="changePage('certificate')" 
                   :class="currentPage === 'certificate' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-award w-4 text-sm"></i>
                    <span>Certificate</span>
                </a>

                <a href="#" @click.prevent="changePage('front-cms')" 
                   :class="currentPage === 'front-cms' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-globe w-4 text-sm"></i>
                    <span>Front CMS</span>
                </a>

                <a href="#" @click.prevent="changePage('live-consultation')" 
                   :class="currentPage === 'live-consultation' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-video w-4 text-sm"></i>
                    <span>Live Consultation</span>
                </a>

                <a href="#" @click.prevent="changePage('reports')" 
                   :class="currentPage === 'reports' ? 'active' : ''"
                   class="sidebar-item flex items-center space-x-2 px-3 py-2 rounded-lg text-sm">
                    <i class="fas fa-chart-bar w-4 text-sm"></i>
                    <span>Reports</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="mt-auto p-3 border-t border-white border-opacity-20">
                <div class="flex items-center space-x-2 p-2 bg-white bg-opacity-10 rounded-lg backdrop-blur-lg">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium truncate"><?= htmlspecialchars($user['name']) ?></p>
                        <p class="text-xs text-white text-opacity-60"><?= ucfirst($user['role']) ?></p>
                    </div>
                    <a href="../pages/login.php?action=logout" class="text-red-300 hover:text-red-100 text-sm">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10 border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h2 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent" x-text="pageTitle"></h2>
                            <div class="flex items-center space-x-3 mt-0.5">
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-calendar-day mr-1 text-purple-500 text-xs"></i>
                                    <span x-text="currentDate"></span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-clock mr-1 text-blue-500 text-xs"></i>
                                    <span x-text="currentTime"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Quick Stats in Header -->
                        <div class="hidden lg:flex items-center space-x-2 mr-3">
                            <div class="bg-blue-50 px-3 py-1.5 rounded-lg">
                                <p class="text-xs text-blue-600">Patients</p>
                                <p class="text-sm font-bold text-blue-700" x-text="stats.totalPatients">0</p>
                            </div>
                            <div class="bg-green-50 px-3 py-1.5 rounded-lg">
                                <p class="text-xs text-green-600">Doctors</p>
                                <p class="text-sm font-bold text-green-700" x-text="stats.totalDoctors">0</p>
                            </div>
                            <div class="bg-purple-50 px-3 py-1.5 rounded-lg">
                                <p class="text-xs text-purple-600">Today</p>
                                <p class="text-sm font-bold text-purple-700" x-text="stats.todayAppointments">0</p>
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" placeholder="Search..." 
                                   class="pl-8 pr-3 py-1.5 w-48 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <i class="fas fa-search text-gray-400 absolute left-2.5 top-2 text-xs"></i>
                        </div>
                        
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:bg-purple-50 rounded-lg transition">
                            <i class="fas fa-bell text-base"></i>
                            <span x-show="notifications > 0" 
                                  class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse"
                                  x-text="notifications"></span>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="flex items-center space-x-2 pl-2 border-l border-gray-300">
                            <div class="text-right">
                                <p class="text-xs font-semibold text-gray-800"><?= htmlspecialchars($user['name']) ?></p>
                                <p class="text-xs text-gray-500"><?= ucfirst($user['role']) ?></p>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
                
                <!-- Dashboard Page -->
                <div x-show="currentPage === 'dashboard'" class="slide-in">
                    <?php include 'pages/dashboard_page.php'; ?>
                </div>

                <!-- Billing Page -->
                <div x-show="currentPage === 'billing'" class="slide-in">
                    <?php include 'pages/billing_page.php'; ?>
                </div>

                <!-- Appointments Page -->
                <div x-show="currentPage === 'appointments'" class="slide-in">
                    <?php include 'pages/appointments_page.php'; ?>
                </div>

                <!-- OPD Page -->
                <div x-show="currentPage === 'opd'" class="slide-in">
                    <?php include 'pages/opd_page.php'; ?>
                </div>

                <!-- IPD Page -->
                <div x-show="currentPage === 'ipd'" class="slide-in">
                    <?php include 'pages/ipd_page.php'; ?>
                </div>

                <!-- Pharmacy Page -->
                <div x-show="currentPage === 'pharmacy'" class="slide-in">
                    <?php include 'pages/pharmacy_page.php'; ?>
                </div>

                <!-- Pathology Page -->
                <div x-show="currentPage === 'pathology'" class="slide-in">
                    <?php include 'pages/pathology_page.php'; ?>
                </div>

                <!-- Radiology Page -->
                <div x-show="currentPage === 'radiology'" class="slide-in">
                    <?php include 'pages/radiology_page.php'; ?>
                </div>

                <!-- Blood Bank Page -->
                <div x-show="currentPage === 'blood-bank'" class="slide-in">
                    <?php include 'pages/blood_bank_page.php'; ?>
                </div>

                <!-- Ambulance Page -->
                <div x-show="currentPage === 'ambulance'" class="slide-in">
                    <?php include 'pages/ambulance_page.php'; ?>
                </div>

                <!-- Front Office Page -->
                <div x-show="currentPage === 'front-office'" class="slide-in">
                    <?php include 'pages/front_office_page.php'; ?>
                </div>

                <!-- Birth & Death Records Page -->
                <div x-show="currentPage === 'birth-death'" class="slide-in">
                    <?php include 'pages/birth_death_page.php'; ?>
                </div>

                <!-- Human Resource Page -->
                <div x-show="currentPage === 'human-resource'" class="slide-in">
                    <?php include 'pages/human_resource_page.php'; ?>
                </div>

                <!-- Duty Roster Page -->
                <div x-show="currentPage === 'duty-roster'" class="slide-in">
                    <?php include 'pages/duty_roster_page.php'; ?>
                </div>

                <!-- Annual Calendar Page -->
                <div x-show="currentPage === 'annual-calendar'" class="slide-in">
                    <?php include 'pages/annual_calendar_page.php'; ?>
                </div>

                <!-- Referral Page -->
                <div x-show="currentPage === 'referral'" class="slide-in">
                    <?php include 'pages/referral_page.php'; ?>
                </div>

                <!-- TPA Management Page -->
                <div x-show="currentPage === 'tpa-management'" class="slide-in">
                    <?php include 'pages/tpa_management_page.php'; ?>
                </div>

                <!-- Finance Page -->
                <div x-show="currentPage === 'finance'" class="slide-in">
                    <?php include 'pages/finance_page.php'; ?>
                </div>

                <!-- Messaging Page -->
                <div x-show="currentPage === 'messaging'" class="slide-in">
                    <?php include 'pages/messaging_page.php'; ?>
                </div>

                <!-- Inventory Page -->
                <div x-show="currentPage === 'inventory'" class="slide-in">
                    <?php include 'pages/inventory_page.php'; ?>
                </div>

                <!-- Download Center Page -->
                <div x-show="currentPage === 'download-center'" class="slide-in">
                    <?php include 'pages/download_center_page.php'; ?>
                </div>

                <!-- Certificate Page -->
                <div x-show="currentPage === 'certificate'" class="slide-in">
                    <?php include 'pages/certificate_page.php'; ?>
                </div>

                <!-- Front CMS Page -->
                <div x-show="currentPage === 'front-cms'" class="slide-in">
                    <?php include 'pages/front_cms_page.php'; ?>
                </div>

                <!-- Live Consultation Page -->
                <div x-show="currentPage === 'live-consultation'" class="slide-in">
                    <?php include 'pages/live_consultation_page.php'; ?>
                </div>

                <!-- Reports Page -->
                <div x-show="currentPage === 'reports'" class="slide-in">
                    <?php include 'pages/reports_page.php'; ?>
                </div>

            </main>
        </div>
    </div>

    <script src="admin_script.js"></script>
</body>
</html>
