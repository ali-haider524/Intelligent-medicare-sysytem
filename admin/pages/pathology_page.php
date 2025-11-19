<div x-data="pathologyManager()" x-init="init()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Pathology Department</h2>
            <p class="text-gray-500">Manage lab tests and reports</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-plus text-sm"></i>
            <span>New Test</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-microscope text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Today</span>
            </div>
            <p class="text-2xl font-bold">28</p>
            <p class="text-xs text-teal-100">Tests Today</p>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-flask text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Pending</span>
            </div>
            <p class="text-2xl font-bold">12</p>
            <p class="text-xs text-blue-100">In Progress</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Ready</span>
            </div>
            <p class="text-2xl font-bold">16</p>
            <p class="text-xs text-green-100">Reports Ready</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-dollar-sign text-2xl opacity-80"></i>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-0.5 rounded-full">Revenue</span>
            </div>
            <p class="text-2xl font-bold">₹8,450</p>
            <p class="text-xs text-purple-100">Today's Income</p>
        </div>
    </div>

    <!-- Test Categories -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Blood Tests</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">CBC:</span>
                    <span class="font-medium">8 tests</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Blood Sugar:</span>
                    <span class="font-medium">5 tests</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Lipid Profile:</span>
                    <span class="font-medium">3 tests</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Urine Tests</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Routine:</span>
                    <span class="font-medium">6 tests</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Culture:</span>
                    <span class="font-medium">2 tests</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Microscopy:</span>
                    <span class="font-medium">4 tests</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Special Tests</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Biopsy:</span>
                    <span class="font-medium">1 test</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Cytology:</span>
                    <span class="font-medium">2 tests</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Histology:</span>
                    <span class="font-medium">1 test</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tests Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Lab Tests</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Test ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Test Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ordered</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono">LAB001</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                    <span class="text-teal-600 font-medium text-xs">JD</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">John Doe</p>
                                    <p class="text-xs text-gray-500">ID: P001</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">CBC</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">Nov 13, 10:30 AM</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Ready</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-1">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="p-1.5 text-green-600 hover:bg-green-50 rounded transition">
                                    <i class="fas fa-file-pdf text-xs"></i>
                                </button>
                                <button class="p-1.5 text-purple-600 hover:bg-purple-50 rounded transition">
                                    <i class="fas fa-print text-xs"></i>
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
function pathologyManager() {
    return {
        tests: [],
        async init() {
            console.log('Pathology Manager initialized');
        }
    }
}
</script>