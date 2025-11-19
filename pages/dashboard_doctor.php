<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Intelligent Medicare System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="doctorDashboard()">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-600">🏥 Doctor Portal</h1>
                    <div class="ml-10 flex space-x-4">
                        <a href="#" @click.prevent="currentTab = 'today'" 
                           :class="currentTab === 'today' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Today's Patients
                        </a>
                        <a href="#" @click.prevent="currentTab = 'appointments'" 
                           :class="currentTab === 'appointments' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            All Appointments
                        </a>
                        <a href="#" @click.prevent="currentTab = 'medicines'" 
                           :class="currentTab === 'medicines' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500'"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Medicine Inventory
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">👨‍⚕️ Dr. <?= htmlspecialchars($user['name']) ?></span>
                    <a href="?action=logout" class="text-sm text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Today's Patients Tab -->
        <div x-show="currentTab === 'today'">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Today's Patient Queue</h2>
                <button @click="loadTodayPatients()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    🔄 Refresh
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Total Today</div>
                    <div class="text-2xl font-bold text-gray-900" x-text="todayStats.total">0</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow p-4">
                    <div class="text-sm text-green-600">Completed</div>
                    <div class="text-2xl font-bold text-green-900" x-text="todayStats.completed">0</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow p-4">
                    <div class="text-sm text-yellow-600">In Progress</div>
                    <div class="text-2xl font-bold text-yellow-900" x-text="todayStats.inProgress">0</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow p-4">
                    <div class="text-sm text-blue-600">Waiting</div>
                    <div class="text-2xl font-bold text-blue-900" x-text="todayStats.waiting">0</div>
                </div>
            </div>

            <!-- Patient Queue -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div x-show="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>

                    <div x-show="!loading && todayPatients.length === 0" class="text-center py-8 text-gray-500">
                        No patients scheduled for today
                    </div>

                    <div x-show="!loading && todayPatients.length > 0" class="space-y-4">
                        <template x-for="(patient, index) in todayPatients" :key="patient.id">
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                                 :class="{'border-l-4 border-l-green-500': patient.status === 'in_progress'}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <span class="text-lg font-bold text-gray-400" x-text="'#' + (index + 1)"></span>
                                            <h4 class="text-lg font-semibold text-gray-900" x-text="patient.patient_name"></h4>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-blue-100 text-blue-800': patient.status === 'scheduled',
                                                      'bg-yellow-100 text-yellow-800': patient.status === 'in_progress',
                                                      'bg-green-100 text-green-800': patient.status === 'completed'
                                                  }"
                                                  x-text="patient.status.replace('_', ' ').toUpperCase()">
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Time:</span>
                                                <span class="font-medium" x-text="formatTime(patient.appointment_date)"></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Phone:</span>
                                                <span class="font-medium" x-text="patient.phone || 'N/A'"></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Blood Group:</span>
                                                <span class="font-medium" x-text="patient.blood_group || 'N/A'"></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Booking Ref:</span>
                                                <span class="font-medium text-xs" x-text="patient.booking_reference"></span>
                                            </div>
                                        </div>
                                        <div x-show="patient.symptoms" class="mt-3 p-3 bg-gray-50 rounded">
                                            <div class="text-sm text-gray-500 mb-1">Symptoms:</div>
                                            <div class="text-sm text-gray-900" x-text="patient.symptoms"></div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex flex-col space-y-2">
                                        <button @click="updateStatus(patient.id, 'in_progress')"
                                                x-show="patient.status === 'scheduled'"
                                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm">
                                            Start Consultation
                                        </button>
                                        <button @click="updateStatus(patient.id, 'completed')"
                                                x-show="patient.status === 'in_progress'"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                            Mark Complete
                                        </button>
                                        <button @click="viewHistory(patient.patient_id)"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                            View History
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Appointments Tab -->
        <div x-show="currentTab === 'appointments'">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">All Appointments</h2>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>Appointment history and upcoming appointments</p>
                </div>
            </div>
        </div>

        <!-- Medicine Inventory Tab -->
        <div x-show="currentTab === 'medicines'">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Medicine Inventory</h2>
                <div class="flex space-x-2">
                    <input type="text" x-model="medicineSearch" @input="searchMedicines()"
                           placeholder="Search medicines..."
                           class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <button @click="loadMedicines()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        🔄 Refresh
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div x-show="medicinesLoading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>

                    <div x-show="!medicinesLoading" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Generic Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Strength</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="medicine in medicines" :key="medicine.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900" x-text="medicine.name"></div>
                                            <div class="text-xs text-gray-500" x-text="medicine.category"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="medicine.generic_name || 'N/A'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="medicine.brand"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="medicine.strength"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full"
                                                  :class="medicine.stock_quantity <= medicine.minimum_stock_level ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                                  x-text="medicine.stock_quantity"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            $<span x-text="medicine.unit_price"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function doctorDashboard() {
            return {
                currentTab: 'today',
                loading: false,
                medicinesLoading: false,
                todayPatients: [],
                medicines: [],
                medicineSearch: '',
                todayStats: {
                    total: 0,
                    completed: 0,
                    inProgress: 0,
                    waiting: 0
                },

                init() {
                    this.loadTodayPatients();
                    this.loadMedicines();
                },

                async loadTodayPatients() {
                    this.loading = true;
                    try {
                        const response = await fetch('../api/appointments.php?action=get_today');
                        const data = await response.json();
                        if (data.success) {
                            this.todayPatients = data.appointments;
                            this.calculateStats();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                    this.loading = false;
                },

                calculateStats() {
                    this.todayStats.total = this.todayPatients.length;
                    this.todayStats.completed = this.todayPatients.filter(p => p.status === 'completed').length;
                    this.todayStats.inProgress = this.todayPatients.filter(p => p.status === 'in_progress').length;
                    this.todayStats.waiting = this.todayPatients.filter(p => p.status === 'scheduled').length;
                },

                async updateStatus(appointmentId, status) {
                    try {
                        const formData = new FormData();
                        formData.append('action', 'update_status');
                        formData.append('appointment_id', appointmentId);
                        formData.append('status', status);

                        const response = await fetch('../api/appointments.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            this.loadTodayPatients();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                },

                async loadMedicines() {
                    this.medicinesLoading = true;
                    try {
                        const response = await fetch('../api/medicines.php?action=get_all');
                        const data = await response.json();
                        if (data.success) {
                            this.medicines = data.medicines;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                    this.medicinesLoading = false;
                },

                async searchMedicines() {
                    if (this.medicineSearch.length < 2) {
                        this.loadMedicines();
                        return;
                    }
                    
                    this.medicinesLoading = true;
                    try {
                        const response = await fetch(`../api/medicines.php?action=search&query=${encodeURIComponent(this.medicineSearch)}`);
                        const data = await response.json();
                        if (data.success) {
                            this.medicines = data.medicines;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                    this.medicinesLoading = false;
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                },

                viewHistory(patientId) {
                    alert('View patient history for ID: ' + patientId);
                }
            }
        }
    </script>
</body>
</html>