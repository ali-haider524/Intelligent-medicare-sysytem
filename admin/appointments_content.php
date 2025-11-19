<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900">Appointments Management</h3>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            + New Appointment
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium">JD</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">John Doe</div>
                                <div class="text-sm text-gray-500">ID: #1234</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Dr. Sarah Smith</div>
                        <div class="text-sm text-gray-500">Cardiology</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Nov 13, 2025</div>
                        <div class="text-sm text-gray-500">10:00 AM</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Confirmed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                        <button class="text-red-600 hover:text-red-900">Cancel</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
