<div x-data="pharmacyManager()" x-init="init()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pharmacy & Inventory</h2>
            <p class="text-gray-500">Manage medicine stock and inventory</p>
        </div>
        <button class="px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Medicine</span>
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm p-2 mb-6 flex space-x-2">
        <button @click="filter = 'all'" 
                :class="filter === 'all' ? 'bg-purple-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium">
            All Medicines (<span x-text="medicines.length">0</span>)
        </button>
        <button @click="filter = 'low_stock'" 
                :class="filter === 'low_stock' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium">
            Low Stock (<span x-text="medicines.filter(m => m.quantity < 100).length">0</span>)
        </button>
        <button @click="filter = 'expiring'" 
                :class="filter === 'expiring' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium">
            Expiring Soon
        </button>
    </div>

    <!-- Medicines Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Medicine</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Expiry</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="medicine in filteredMedicines" :key="medicine.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-pills text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="medicine.name"></p>
                                        <p class="text-xs text-gray-500" x-text="medicine.brand || 'Generic'"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800" 
                                      x-text="medicine.category || 'General'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold" 
                                   :class="medicine.quantity < 100 ? 'text-red-600' : 'text-green-600'"
                                   x-text="medicine.quantity"></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">₹<span x-text="medicine.unit_price"></span></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900" x-text="formatDate(medicine.expiry_date)"></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full"
                                      :class="medicine.quantity < 100 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                      x-text="medicine.quantity < 100 ? 'Low Stock' : 'In Stock'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                    <button class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function pharmacyManager() {
    return {
        medicines: [],
        filter: 'all',
        
        get filteredMedicines() {
            if (this.filter === 'all') return this.medicines;
            if (this.filter === 'low_stock') return this.medicines.filter(m => m.quantity < 100);
            return this.medicines;
        },
        
        async init() {
            try {
                const response = await fetch('../api/medicines.php?action=get_all');
                const data = await response.json();
                if (data.success) {
                    this.medicines = data.medicines || [];
                }
            } catch (error) {
                console.error('Error loading medicines:', error);
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }
    }
}
</script>
