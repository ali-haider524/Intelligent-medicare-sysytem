<!-- Modern Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Patients Card -->
    <div class="card-hover bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-lg">
                <i class="fas fa-users text-lg"></i>
            </div>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">+12%</span>
        </div>
        <h3 class="text-xs font-medium text-blue-100 mb-1">Total Patients</h3>
        <p class="text-2xl font-bold" x-text="stats.totalPatients">0</p>
        <p class="text-xs text-blue-100 mt-1">Registered in system</p>
    </div>

    <!-- Total Doctors Card -->
    <div class="card-hover bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-lg">
                <i class="fas fa-user-md text-lg"></i>
            </div>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Active</span>
        </div>
        <h3 class="text-xs font-medium text-green-100 mb-1">Total Doctors</h3>
        <p class="text-2xl font-bold" x-text="stats.totalDoctors">0</p>
        <p class="text-xs text-green-100 mt-1">Medical professionals</p>
    </div>

    <!-- Today's Appointments Card -->
    <div class="card-hover bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-lg">
                <i class="fas fa-calendar-check text-lg"></i>
            </div>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Today</span>
        </div>
        <h3 class="text-xs font-medium text-purple-100 mb-1">Appointments</h3>
        <p class="text-2xl font-bold" x-text="stats.todayAppointments">0</p>
        <p class="text-xs text-purple-100 mt-1">Scheduled for today</p>
    </div>

    <!-- Revenue Card -->
    <div class="card-hover bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 text-white shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-lg">
                <i class="fas fa-dollar-sign text-lg"></i>
            </div>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">+8%</span>
        </div>
        <h3 class="text-xs font-medium text-orange-100 mb-1">Today's Revenue</h3>
        <p class="text-2xl font-bold">₹<span x-text="stats.todayRevenue.toLocaleString()">0</span></p>
        <p class="text-xs text-orange-100 mt-1">Total earnings today</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow p-4 card-hover">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Weekly Revenue</h3>
                <p class="text-xs text-gray-500">Last 7 days performance</p>
            </div>
            <div class="flex space-x-1">
                <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-download text-gray-600 text-xs"></i>
                </button>
                <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-ellipsis-v text-gray-600 text-xs"></i>
                </button>
            </div>
        </div>
        <div style="height: 250px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Appointments Chart -->
    <div class="bg-white rounded-xl shadow p-4 card-hover">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Weekly Appointments</h3>
                <p class="text-xs text-gray-500">Appointment trends</p>
            </div>
            <div class="flex space-x-1">
                <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-download text-gray-600 text-xs"></i>
                </button>
                <button class="p-1.5 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-ellipsis-v text-gray-600 text-xs"></i>
                </button>
            </div>
        </div>
        <div style="height: 250px;">
            <canvas id="appointmentsChart"></canvas>
        </div>
    </div>
</div>

<!-- Department Performance & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Department Performance -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 card-hover">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Department Performance</h3>
        <div class="space-y-4">
            <?php
            $departments = [
                ['name' => 'Cardiology', 'icon' => 'fa-heart', 'color' => 'red', 'patients' => 45, 'revenue' => 125000],
                ['name' => 'Neurology', 'icon' => 'fa-brain', 'color' => 'blue', 'patients' => 38, 'revenue' => 98000],
                ['name' => 'Orthopedics', 'icon' => 'fa-bone', 'color' => 'green', 'patients' => 52, 'revenue' => 87000],
                ['name' => 'Pediatrics', 'icon' => 'fa-baby', 'color' => 'yellow', 'patients' => 61, 'revenue' => 76000],
            ];
            
            foreach ($departments as $dept):
            ?>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-<?= $dept['color'] ?>-100 rounded-xl flex items-center justify-center">
                        <i class="fas <?= $dept['icon'] ?> text-<?= $dept['color'] ?>-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800"><?= $dept['name'] ?></p>
                        <p class="text-sm text-gray-500"><?= $dept['patients'] ?> patients this month</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-800">₹<?= number_format($dept['revenue']) ?></p>
                    <p class="text-sm text-green-600">↑ 12%</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Quick Actions & Department Chart -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button @click="changePage('appointments')" 
                        class="w-full flex items-center space-x-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition text-left">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-white"></i>
                    </div>
                    <span class="font-medium text-blue-900">New Appointment</span>
                </button>
                
                <button @click="changePage('patients')" 
                        class="w-full flex items-center space-x-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition text-left">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <span class="font-medium text-green-900">Add Patient</span>
                </button>
                
                <button @click="changePage('pharmacy')" 
                        class="w-full flex items-center space-x-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition text-left">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-white"></i>
                    </div>
                    <span class="font-medium text-purple-900">Manage Inventory</span>
                </button>
                
                <button @click="changePage('reports')" 
                        class="w-full flex items-center space-x-3 p-3 bg-orange-50 hover:bg-orange-100 rounded-xl transition text-left">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <span class="font-medium text-orange-900">Generate Report</span>
                </button>
            </div>
        </div>

        <!-- Department Distribution -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Department Distribution</h3>
            <div style="height: 200px;">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Recent Activity</h3>
        <div class="space-y-4">
            <?php
            $activities = [
                ['icon' => 'fa-user-plus', 'color' => 'green', 'text' => 'New patient registered', 'time' => '2 minutes ago', 'user' => 'John Doe'],
                ['icon' => 'fa-calendar-check', 'color' => 'blue', 'text' => 'Appointment completed', 'time' => '15 minutes ago', 'user' => 'Dr. Smith'],
                ['icon' => 'fa-pills', 'color' => 'purple', 'text' => 'Medicine stock updated', 'time' => '1 hour ago', 'user' => 'Pharmacy'],
                ['icon' => 'fa-file-invoice', 'color' => 'orange', 'text' => 'Invoice generated', 'time' => '2 hours ago', 'user' => 'Billing Dept'],
            ];
            
            foreach ($activities as $activity):
            ?>
            <div class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-xl transition">
                <div class="w-10 h-10 bg-<?= $activity['color'] ?>-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas <?= $activity['icon'] ?> text-<?= $activity['color'] ?>-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800"><?= $activity['text'] ?></p>
                    <p class="text-xs text-gray-500"><?= $activity['user'] ?> • <?= $activity['time'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
        <h3 class="text-lg font-bold text-gray-800 mb-6">System Alerts</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <i class="fas fa-exclamation-triangle text-red-600 mt-1"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-900">Low Stock Alert</p>
                    <p class="text-xs text-red-700 mt-1">3 medicines are running low on stock</p>
                    <button @click="changePage('pharmacy')" class="text-xs text-red-600 hover:text-red-800 mt-2 font-medium">
                        View Details →
                    </button>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                <i class="fas fa-clock text-yellow-600 mt-1"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-900">Pending Appointments</p>
                    <p class="text-xs text-yellow-700 mt-1">15 appointments waiting for confirmation</p>
                    <button @click="changePage('appointments')" class="text-xs text-yellow-600 hover:text-yellow-800 mt-2 font-medium">
                        Review Now →
                    </button>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-900">System Update</p>
                    <p class="text-xs text-blue-700 mt-1">New features available in the admin panel</p>
                    <button class="text-xs text-blue-600 hover:text-blue-800 mt-2 font-medium">
                        Learn More →
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
