<div x-data="appointmentsManager()" x-init="init()">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Appointments Management</h2>
            <p class="text-gray-500">Manage and track all patient appointments</p>
        </div>
        <button @click="showNewAppointmentModal = true" 
                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
            <i class="fas fa-plus text-sm"></i>
            <span>New Appointment</span>
        </button>
    </div>

    <!-- Stats Cards - Enhanced Visibility -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Total</p>
                    <p class="text-3xl font-bold text-gray-900" x-text="appointments.length">0</p>
                    <p class="text-xs text-gray-600 mt-1">All Appointments</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Completed</p>
                    <p class="text-3xl font-bold text-green-600" x-text="appointments.filter(a => a.status === 'completed').length">0</p>
                    <p class="text-xs text-gray-600 mt-1">Done</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Scheduled</p>
                    <p class="text-3xl font-bold text-yellow-600" x-text="appointments.filter(a => a.status === 'scheduled').length">0</p>
                    <p class="text-xs text-gray-600 mt-1">Pending</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-red-500 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Cancelled</p>
                    <p class="text-3xl font-bold text-red-600" x-text="appointments.filter(a => a.status === 'cancelled').length">0</p>
                    <p class="text-xs text-gray-600 mt-1">Cancelled</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm p-2 mb-6 flex space-x-2">
        <button @click="changeFilter('all')" 
                :class="filter === 'all' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium text-sm">
            All (<span x-text="appointments.length">0</span>)
        </button>
        <button @click="changeFilter('scheduled')" 
                :class="filter === 'scheduled' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium text-sm">
            Scheduled (<span x-text="appointments.filter(a => a.status === 'scheduled').length">0</span>)
        </button>
        <button @click="changeFilter('completed')" 
                :class="filter === 'completed' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium text-sm">
            Completed (<span x-text="appointments.filter(a => a.status === 'completed').length">0</span>)
        </button>
        <button @click="changeFilter('cancelled')" 
                :class="filter === 'cancelled' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg transition font-medium text-sm">
            Cancelled (<span x-text="appointments.filter(a => a.status === 'cancelled').length">0</span>)
        </button>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="appointment in filteredAppointments" :key="appointment.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-600" x-text="'#' + appointment.id"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="appointment.patient_name"></p>
                                        <p class="text-xs text-gray-500" x-text="appointment.patient_email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900" x-text="appointment.doctor_name"></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900" x-text="formatDate(appointment.appointment_date)"></p>
                                <p class="text-xs text-gray-500" x-text="formatTime(appointment.appointment_time)"></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800" 
                                      x-text="appointment.department || 'General'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-green-100 text-green-800': appointment.status === 'completed',
                                          'bg-blue-100 text-blue-800': appointment.status === 'scheduled',
                                          'bg-yellow-100 text-yellow-800': appointment.status === 'pending',
                                          'bg-red-100 text-red-800': appointment.status === 'cancelled'
                                      }"
                                      x-text="appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button @click="viewAppointment(appointment)" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="editAppointment(appointment)" 
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteAppointment(appointment.id)" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div x-show="filteredAppointments.length === 0" class="text-center py-12">
            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No appointments found</p>
        </div>
    </div>

    <!-- New Appointment Modal -->
    <div x-show="showNewAppointmentModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showNewAppointmentModal = false">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">New Appointment</h3>
                    <button @click="showNewAppointmentModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="createNewAppointment()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name</label>
                            <input type="text" x-model="newAppointment.patient_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                            <select x-model="newAppointment.doctor_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Doctor</option>
                                <option value="1">Dr. Smith</option>
                                <option value="2">Dr. Johnson</option>
                                <option value="3">Dr. Brown</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" x-model="newAppointment.date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                            <input type="time" x-model="newAppointment.time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit</label>
                            <textarea x-model="newAppointment.reason" rows="4" placeholder="Enter reason for visit..."
                                      style="color: #1f2937; background-color: #ffffff;"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showNewAppointmentModal = false"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function appointmentsManager() {
    return {
        appointments: [],
        filter: 'all',
        showNewAppointmentModal: false,
        loading: false,
        newAppointment: {
            patient_name: '',
            doctor_id: '',
            date: '',
            time: '',
            reason: ''
        },
        
        get filteredAppointments() {
            if (this.filter === 'all') return this.appointments;
            return this.appointments.filter(a => a.status === this.filter);
        },
        
        async init() {
            console.log('🚀 Appointments Manager Initialized');
            await this.loadAppointments();
        },
        
        async loadAppointments() {
            this.loading = true;
            try {
                console.log('📥 Loading appointments...');
                const response = await fetch('../api/appointments.php?action=get_all');
                const data = await response.json();
                console.log('📊 Appointments data:', data);
                
                if (data.success) {
                    this.appointments = data.appointments || [];
                    console.log('✅ Loaded', this.appointments.length, 'appointments');
                } else {
                    console.error('❌ API Error:', data.message);
                }
            } catch (error) {
                console.error('❌ Network Error:', error);
            }
            this.loading = false;
        },
        
        async changeFilter(newFilter) {
            console.log('🔄 Changing filter to:', newFilter);
            this.filter = newFilter;
            
            if (newFilter === 'all') {
                await this.loadAppointments();
            } else {
                try {
                    const response = await fetch(`../api/appointments.php?action=get_by_status&status=${newFilter}`);
                    const data = await response.json();
                    if (data.success) {
                        this.appointments = data.appointments || [];
                    }
                } catch (error) {
                    console.error('Error filtering appointments:', error);
                }
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        },
        
        formatTime(timeString) {
            if (!timeString) return 'N/A';
            const time = new Date('2000-01-01 ' + timeString);
            return time.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true
            });
        },
        
        viewAppointment(appointment) {
            alert(`Viewing Appointment Details:\n\nID: ${appointment.id}\nPatient: ${appointment.patient_name}\nDoctor: ${appointment.doctor_name}\nDate: ${this.formatDate(appointment.appointment_date)}\nTime: ${this.formatTime(appointment.appointment_time)}\nStatus: ${appointment.status}`);
        },
        
        editAppointment(appointment) {
            const newStatus = prompt(`Change status for appointment #${appointment.id}:\n\nCurrent: ${appointment.status}\n\nEnter new status (scheduled/completed/cancelled):`, appointment.status);
            
            if (newStatus && newStatus !== appointment.status) {
                this.updateAppointmentStatus(appointment.id, newStatus);
            }
        },
        
        async updateAppointmentStatus(id, status) {
            try {
                const response = await fetch('../api/appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update', id, status })
                });
                
                const data = await response.json();
                if (data.success) {
                    await this.loadAppointments();
                    alert('Appointment status updated successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error updating appointment:', error);
                alert('Network error occurred');
            }
        },
        
        async deleteAppointment(id) {
            if (!confirm('Are you sure you want to delete this appointment?\n\nThis action cannot be undone.')) return;
            
            try {
                const response = await fetch('../api/appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id })
                });
                
                const data = await response.json();
                if (data.success) {
                    await this.loadAppointments();
                    alert('Appointment deleted successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting appointment:', error);
                alert('Network error occurred');
            }
        },
        
        async createNewAppointment() {
            try {
                if (!this.newAppointment.patient_name || !this.newAppointment.doctor_id || 
                    !this.newAppointment.date || !this.newAppointment.time) {
                    alert('Please fill all required fields');
                    return;
                }
                
                // Get or create patient ID
                const patientResponse = await fetch('../api/patients.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'get_or_create',
                        name: this.newAppointment.patient_name,
                        email: this.newAppointment.patient_name.toLowerCase().replace(/\s+/g, '.') + '@patient.com'
                    })
                });
                
                const patientData = await patientResponse.json();
                const patientId = patientData.patient_id || 1;
                
                // Create appointment
                const response = await fetch('../api/appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'create',
                        patient_id: patientId,
                        doctor_id: this.newAppointment.doctor_id,
                        department_id: 1,
                        appointment_date: this.newAppointment.date,
                        appointment_time: this.newAppointment.time,
                        reason: this.newAppointment.reason
                    })
                });
                
                const data = await response.json();
                console.log('Create response:', data);
                
                if (data.success) {
                    // Close modal
                    this.showNewAppointmentModal = false;
                    
                    // Reset form
                    this.newAppointment = {
                        patient_name: '',
                        doctor_id: '',
                        date: '',
                        time: '',
                        reason: ''
                    };
                    
                    // Reload appointments to show new one
                    await this.loadAppointments();
                    
                    // Show success message
                    alert('✅ Appointment created successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to create appointment'));
                }
                
            } catch (error) {
                console.error('Error creating appointment:', error);
                alert('Network error: ' + error.message);
            }
        }
    }
}
</script>
