<div x-data="patientsManager()" x-init="init()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Patient Management</h2>
            <p class="text-gray-500">View and manage all patient records</p>
        </div>
        <button class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-user-plus"></i>
            <span>Add New Patient</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-80"></i>
                <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Total</span>
            </div>
            <p class="text-3xl font-bold" x-text="patients.length">0</p>
            <p class="text-sm text-blue-100">Registered Patients</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-check text-3xl opacity-80"></i>
                <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Active</span>
            </div>
            <p class="text-3xl font-bold" x-text="patients.filter(p => p.status === 'active').length">0</p>
            <p class="text-sm text-green-100">Active Patients</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-calendar-day text-3xl opacity-80"></i>
                <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Today</span>
            </div>
            <p class="text-3xl font-bold">12</p>
            <p class="text-sm text-purple-100">New Today</p>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-heartbeat text-3xl opacity-80"></i>
                <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Critical</span>
            </div>
            <p class="text-3xl font-bold">3</p>
            <p class="text-sm text-orange-100">Need Attention</p>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="relative flex-1 max-w-md">
                    <input type="text" x-model="searchQuery" placeholder="Search patients by name, email, phone..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search text-gray-400 absolute left-3 top-3"></i>
                </div>
                <div class="flex items-center space-x-2">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Patient ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Age/Gender</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Last Visit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="patient in filteredPatients" :key="patient.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-600" x-text="'#' + patient.id"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        <span x-text="patient.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="patient.name"></p>
                                        <p class="text-xs text-gray-500" x-text="patient.email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900" x-text="patient.phone || 'N/A'"></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">
                                    <span x-text="calculateAge(patient.date_of_birth)"></span> yrs / 
                                    <span x-text="patient.gender || 'N/A'"></span>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900" x-text="formatDate(patient.last_visit)"></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Medical History">
                                        <i class="fas fa-file-medical"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <div x-show="filteredPatients.length === 0" class="text-center py-12">
            <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No patients found</p>
        </div>
    </div>
</div>

<script>
function patientsManager() {
    return {
        patients: [],
        searchQuery: '',
        
        get filteredPatients() {
            if (!this.searchQuery) return this.patients;
            const query = this.searchQuery.toLowerCase();
            return this.patients.filter(p => 
                p.name.toLowerCase().includes(query) ||
                p.email.toLowerCase().includes(query) ||
                (p.phone && p.phone.includes(query))
            );
        },
        
        async init() {
            await this.loadPatients();
        },
        
        async loadPatients() {
            try {
                const response = await fetch('../api/patients.php?action=get_all');
                const data = await response.json();
                if (data.success) {
                    this.patients = data.patients || [];
                }
            } catch (error) {
                console.error('Error loading patients:', error);
            }
        },
        
        calculateAge(dob) {
            if (!dob) return 'N/A';
            const today = new Date();
            const birthDate = new Date(dob);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        },
        
        formatDate(dateString) {
            if (!dateString) return 'Never';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }
    }
}
</script>
