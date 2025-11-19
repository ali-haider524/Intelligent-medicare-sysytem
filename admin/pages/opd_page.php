<div x-data="opdManager()" x-init="init()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">OPD - Out Patient Department</h2>
            <p class="text-gray-500">Manage outpatient consultations and visits</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-plus text-sm"></i>
            <span>New OPD Visit</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-procedures text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Today</span>
            </div>
            <p class="text-2xl font-bold">45</p>
            <p class="text-xs text-blue-100">OPD Visits Today</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-check text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Active</span>
            </div>
            <p class="text-2xl font-bold">12</p>
            <p class="text-xs text-green-100">In Progress</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-clock text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Queue</span>
            </div>
            <p class="text-2xl font-bold">8</p>
            <p class="text-xs text-purple-100">Waiting</p>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-dollar-sign text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Revenue</span>
            </div>
            <p class="text-2xl font-bold">₹15,240</p>
            <p class="text-xs text-orange-100">Today's Income</p>
        </div>
    </div>

    <!-- OPD Queue -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Current OPD Queue</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-800">Token #001</span>
                    <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded">In Progress</span>
                </div>
                <p class="text-sm text-gray-700">John Doe</p>
                <p class="text-xs text-gray-500">Dr. Smith - Cardiology</p>
            </div>
            
            <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-yellow-800">Token #002</span>
                    <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Waiting</span>
                </div>
                <p class="text-sm text-gray-700">Jane Smith</p>
                <p class="text-xs text-gray-500">Dr. Johnson - General</p>
            </div>
            
            <div class="border-l-4 border-gray-500 bg-gray-50 p-4 rounded">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-800">Token #003</span>
                    <span class="text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded">Scheduled</span>
                </div>
                <p class="text-sm text-gray-700">Mike Wilson</p>
                <p class="text-xs text-gray-500">Dr. Brown - Neurology</p>
            </div>
        </div>
    </div>

    <!-- OPD Visits Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">OPD Visits</h3>
                <div class="flex items-center space-x-2">
                    <select class="px-3 py-1 border border-gray-300 rounded text-sm">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Neurology</option>
                        <option>General</option>
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Token</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Doctor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono">#001</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-xs">JD</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">John Doe</p>
                                    <p class="text-xs text-gray-500">ID: P001</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">Dr. Smith</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Cardiology</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">10:30 AM</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">In Progress</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-1">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="p-1.5 text-green-600 hover:bg-green-50 rounded transition">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="p-1.5 text-purple-600 hover:bg-purple-50 rounded transition">
                                    <i class="fas fa-file-medical text-xs"></i>
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
function opdManager() {
    return {
        visits: [],
        
        async init() {
            // Load OPD data
            console.log('OPD Manager initialized');
        }
    }
}
</script>