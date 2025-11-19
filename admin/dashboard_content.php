<!-- Income Cards Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mb-6">
    <!-- OPD Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-procedures text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">OPD Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- IPD Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-cyan-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-bed text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">IPD Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Pharmacy Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-pills text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Pharmacy Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Pathology Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-microscope text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Pathology Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Radiology Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-x-ray text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Radiology Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Blood Bank Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-tint text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Blood Bank Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Ambulance Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-ambulance text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Ambulance Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- General Income -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-dollar-sign text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">General Income</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>

    <!-- Expenses -->
    <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
        <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-minus-circle text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">Expenses</p>
            <p class="text-lg font-bold text-gray-900">₹0.00</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Yearly Income & Expense Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Yearly Income & Expense</h3>
            <div class="flex space-x-2">
                <button class="p-1 hover:bg-gray-100 rounded"><i class="fas fa-minus text-gray-400"></i></button>
                <button class="p-1 hover:bg-gray-100 rounded"><i class="fas fa-times text-gray-400"></i></button>
            </div>
        </div>
        <canvas id="yearlyChart" height="80"></canvas>
    </div>

    <!-- Monthly Income Overview Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Monthly Income Overview</h3>
            <div class="flex space-x-2">
                <button class="p-1 hover:bg-gray-100 rounded"><i class="fas fa-minus text-gray-400"></i></button>
                <button class="p-1 hover:bg-gray-100 rounded"><i class="fas fa-times text-gray-400"></i></button>
            </div>
        </div>
        <canvas id="monthlyChart" height="80"></canvas>
    </div>
</div>

<!-- Calendar Section -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Calendar
        </h3>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded"><i class="fas fa-chevron-left"></i></button>
            <button class="px-4 py-1 text-sm bg-blue-600 text-white rounded">Today</button>
            <button class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded"><i class="fas fa-chevron-right"></i></button>
            <div class="flex border border-gray-300 rounded overflow-hidden ml-2">
                <button class="px-3 py-1 text-sm bg-white hover:bg-gray-100">Month</button>
                <button class="px-3 py-1 text-sm bg-white hover:bg-gray-100">Week</button>
                <button class="px-3 py-1 text-sm bg-blue-600 text-white">Day</button>
            </div>
        </div>
    </div>
    
    <div class="text-center py-4 mb-4 bg-gray-50 rounded">
        <p class="text-2xl font-bold text-gray-900">November 9 – 15 2025</p>
    </div>

    <!-- Calendar Grid -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-xs font-medium text-gray-600">Time</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Sun 11/9</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Mon 11/10</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Tue 11/11</th>
                    <th class="border p-2 text-xs font-medium text-gray-600 bg-yellow-100">Wed 11/12</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Thu 11/13</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Fri 11/14</th>
                    <th class="border p-2 text-xs font-medium text-gray-600">Sat 11/15</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="border p-2 text-xs text-gray-500">12am</td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2 bg-yellow-50"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td></tr>
                <tr><td class="border p-2 text-xs text-gray-500">6am</td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2 bg-yellow-50"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td></tr>
                <tr><td class="border p-2 text-xs text-gray-500">12pm</td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2 bg-yellow-50"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td></tr>
                <tr><td class="border p-2 text-xs text-gray-500">6pm</td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2 bg-yellow-50"></td><td class="border p-2"></td><td class="border p-2"></td><td class="border p-2"></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Activity & Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-green-600 text-sm">✓</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">New patient registered</p>
                    <p class="text-xs text-gray-500">John Doe - 2 minutes ago</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-600 text-sm">📅</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Appointment completed</p>
                    <p class="text-xs text-gray-500">Dr. Smith with Patient #1234 - 5 minutes ago</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-yellow-600 text-sm">💊</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Medicine stock updated</p>
                    <p class="text-xs text-gray-500">Paracetamol - 10 minutes ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Alerts</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                <div class="w-6 h-6 text-red-600 flex-shrink-0">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-900">Low Stock Alert</p>
                    <p class="text-xs text-red-700">3 medicines are running low</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                <div class="w-6 h-6 text-yellow-600 flex-shrink-0">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-yellow-900">Appointment Reminder</p>
                    <p class="text-xs text-yellow-700">15 appointments scheduled for tomorrow</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                <div class="w-6 h-6 text-blue-600 flex-shrink-0">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-900">System Update</p>
                    <p class="text-xs text-blue-700">New features available in admin panel</p>
                </div>
            </div>
        </div>
    </div>
</div>
