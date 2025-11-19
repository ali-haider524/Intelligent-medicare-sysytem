<div x-data="ipdManager()" x-init="init()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">IPD - In Patient Department</h2>
            <p class="text-gray-500">Manage admitted patients and bed allocation</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-plus text-sm"></i>
            <span>New Admission</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-bed text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Total</span>
            </div>
            <p class="text-2xl font-bold">120</p>
            <p class="text-xs text-green-100">Total Beds</p>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-injured text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Occupied</span>
            </div>
            <p class="text-2xl font-bold">85</p>
            <p class="text-xs text-blue-100">Admitted Patients</p>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-bed text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Available</span>
            </div>
            <p class="text-2xl font-bold">35</p>
            <p class="text-xs text-yellow-100">Available Beds</p>
        </div>
        
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-exclamation-triangle text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Critical</span>
            </div>
            <p class="text-2xl font-bold">3</p>
            <p class="text-xs text-red-100">Critical Patients</p>
        </div>
    </div>

    <!-- Ward Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">General Ward</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Beds:</span>
                    <span class="font-medium">60</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Occupied:</span>
                    <span class="font-medium text-blue-600">45</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Available:</span>
                    <span class="font-medium text-green-600">15</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
                <p class="text-xs text-gray-500">75% Occupancy</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">ICU</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Beds:</span>
                    <span class="font-medium">20</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Occupied:</span>
                    <span class="font-medium text-red-600">18</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Available:</span>
                    <span class="font-medium text-green-600">2</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-600 h-2 rounded-full" style="width: 90%"></div>
                </div>
                <p class="text-xs text-gray-500">90% Occupancy</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Private Rooms</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Beds:</span>
                    <span class="font-medium">40</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Occupied:</span>
                    <span class="font-medium text-blue-600">22</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Available:</span>
                    <span class="font-medium text-green-600">18</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 55%"></div>
                </div>
                <p class="text-xs text-gray-500">55% Occupancy</p>
            </div>
        </div>
    </div>

    <!-- Admitted Patients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Admitted Patients</h3>
                <div class="flex items-center space-x-2">
                    <select class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option>All Wards</option>
                        <option>General Ward</option>
                        <option>ICU</option>
                        <option>Private Rooms</option>
                    </select>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bed No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ward</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Admitted</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Doctor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Condition</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-medium text-xs">AS</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Alice Smith</p>
                                    <p class="text-xs text-gray-500">ID: P045</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-mono">G-101</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">General Ward</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">Nov 10, 2025</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Dr. Johnson</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Stable</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-1">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="p-1.5 text-green-600 hover:bg-green-50 rounded transition">
                                    <i class="fas fa-notes-medical text-xs"></i>
                                </button>
                                <button class="p-1.5 text-orange-600 hover:bg-orange-50 rounded transition">
                                    <i class="fas fa-sign-out-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function ipdManager() {
    return {
        patients: [],
        
        async init() {
            console.log('IPD Manager initialized');
        }
    }
}
</script>