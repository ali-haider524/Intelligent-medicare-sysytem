<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900">Medicine Inventory</h3>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            + Add Medicine
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-blue-600 mb-1">Total Medicines</p>
            <p class="text-2xl font-bold text-blue-900">245</p>
        </div>
        <div class="bg-red-50 rounded-lg p-4">
            <p class="text-sm text-red-600 mb-1">Low Stock</p>
            <p class="text-2xl font-bold text-red-900">3</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-green-600 mb-1">In Stock</p>
            <p class="text-2xl font-bold text-green-900">242</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Paracetamol 500mg</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">Pain Relief</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">1,250 units</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$2.50</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            In Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <button class="text-green-600 hover:text-green-900">Restock</button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Amoxicillin 250mg</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">Antibiotic</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-red-600 font-medium">45 units</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$5.75</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Low Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <button class="text-green-600 hover:text-green-900">Restock</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
