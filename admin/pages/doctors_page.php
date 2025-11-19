<div x-data="doctorsManager()" x-init="init()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Doctor Management</h2>
            <p class="text-gray-500">Manage medical staff and their schedules</p>
        </div>
        <button class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-user-md-plus"></i>
            <span>Add New Doctor</span>
        </button>
    </div>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="doctor in doctors" :key="doctor.id">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition card-hover">
                <div class="h-32 bg-gradient-to-r from-green-400 to-blue-500"></div>
                <div class="p-6 -mt-16">
                    <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-md text-4xl text-green-600"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-800" x-text="doctor.name"></h3>
                        <p class="text-sm text-gray-500" x-text="doctor.specialization || 'General Physician'"></p>
                        <div class="flex items-center justify-center space-x-2 mt-2">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Available</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full" x-text="doctor.experience || '5'">5</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span><i class="fas fa-envelope mr-2"></i>Email</span>
                            <span class="text-gray-800" x-text="doctor.email"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span><i class="fas fa-users mr-2"></i>Patients</span>
                            <span class="text-gray-800">45</span>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button class="flex-1 px-4 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition">
                            <i class="fas fa-eye mr-2"></i>View
                        </button>
                        <button class="flex-1 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-calendar mr-2"></i>Schedule
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    
    <div x-show="doctors.length === 0" class="text-center py-20">
        <i class="fas fa-user-md text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500">No doctors found</p>
    </div>
</div>

<script>
function doctorsManager() {
    return {
        doctors: [],
        async init() {
            try {
                const response = await fetch('../api/doctors.php?action=get_all');
                const data = await response.json();
                if (data.success) {
                    this.doctors = data.doctors || [];
                }
            } catch (error) {
                console.error('Error loading doctors:', error);
            }
        }
    }
}
</script>
