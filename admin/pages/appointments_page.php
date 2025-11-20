<div x-data="appointmentsManager()" x-init="init()">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Appointments Management</h2>
            <p class="text-gray-500">Manage and track all patient appointments</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showEmergencyModal = true" 
                    class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
                <i class="fas fa-ambulance text-sm"></i>
                <span>🚨 Emergency</span>
            </button>
            <button @click="showNewAppointmentModal = true" 
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition flex items-center space-x-2">
                <i class="fas fa-plus text-sm"></i>
                <span>New Appointment</span>
            </button>
        </div>
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
                        <!-- Patient Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name *</label>
                            <input type="text" x-model="newAppointment.patient_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" x-model="newAppointment.phone" required placeholder="+1234567890"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (Optional)</label>
                            <input type="email" x-model="newAppointment.email" placeholder="patient@example.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor *</label>
                            <select x-model="newAppointment.doctor_id" required @change="updateConsultationFee()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Doctor</option>
                                <option value="1" data-fee="500">Dr. Smith - General Medicine (₹500)</option>
                                <option value="2" data-fee="800">Dr. Johnson - Cardiology (₹800)</option>
                                <option value="3" data-fee="1000">Dr. Brown - Neurology (₹1000)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                            <input type="date" x-model="newAppointment.date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                            <input type="time" x-model="newAppointment.time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Symptoms/Reason for Visit *</label>
                            <select x-model="newAppointment.reason" required @change="handleReasonChange()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select symptoms/reason</option>
                                
                                <!-- General Medicine Symptoms -->
                                <template x-if="newAppointment.doctor_id === '1'">
                                    <optgroup label="General Medicine Symptoms">
                                        <option value="Routine Health Checkup">Routine Health Checkup</option>
                                        <option value="Fever and Cold">Fever and Cold</option>
                                        <option value="Body Pain/Muscle Ache">Body Pain/Muscle Ache</option>
                                        <option value="Headache">Headache</option>
                                        <option value="Stomach Problems">Stomach Problems</option>
                                        <option value="Blood Pressure Issues">Blood Pressure Issues</option>
                                        <option value="Diabetes Consultation">Diabetes Consultation</option>
                                        <option value="General Health Screening">General Health Screening</option>
                                    </optgroup>
                                </template>
                                
                                <!-- Cardiology Symptoms -->
                                <template x-if="newAppointment.doctor_id === '2'">
                                    <optgroup label="Cardiology Symptoms">
                                        <option value="Chest Pain">Chest Pain</option>
                                        <option value="Heart Palpitations">Heart Palpitations</option>
                                        <option value="High Blood Pressure">High Blood Pressure</option>
                                        <option value="Shortness of Breath">Shortness of Breath</option>
                                        <option value="Heart Disease Follow-up">Heart Disease Follow-up</option>
                                        <option value="ECG/Echo Test">ECG/Echo Test</option>
                                        <option value="Cholesterol Management">Cholesterol Management</option>
                                        <option value="Cardiac Risk Assessment">Cardiac Risk Assessment</option>
                                    </optgroup>
                                </template>
                                
                                <!-- Neurology Symptoms -->
                                <template x-if="newAppointment.doctor_id === '3'">
                                    <optgroup label="Neurology Symptoms">
                                        <option value="Severe Headache/Migraine">Severe Headache/Migraine</option>
                                        <option value="Dizziness/Vertigo">Dizziness/Vertigo</option>
                                        <option value="Memory Problems">Memory Problems</option>
                                        <option value="Seizures">Seizures</option>
                                        <option value="Nerve Pain/Numbness">Nerve Pain/Numbness</option>
                                        <option value="Sleep Disorders">Sleep Disorders</option>
                                        <option value="Brain/Spine Issues">Brain/Spine Issues</option>
                                        <option value="Neurological Examination">Neurological Examination</option>
                                    </optgroup>
                                </template>
                                
                                <!-- Emergency Options -->
                                <optgroup label="Emergency Cases">
                                    <option value="EMERGENCY - Severe Chest Pain" style="color: red; font-weight: bold;">🚨 EMERGENCY - Severe Chest Pain</option>
                                    <option value="EMERGENCY - Difficulty Breathing" style="color: red; font-weight: bold;">🚨 EMERGENCY - Difficulty Breathing</option>
                                    <option value="EMERGENCY - Severe Head Injury" style="color: red; font-weight: bold;">🚨 EMERGENCY - Severe Head Injury</option>
                                    <option value="EMERGENCY - Unconscious/Fainting" style="color: red; font-weight: bold;">🚨 EMERGENCY - Unconscious/Fainting</option>
                                    <option value="EMERGENCY - Severe Bleeding" style="color: red; font-weight: bold;">🚨 EMERGENCY - Severe Bleeding</option>
                                </optgroup>
                                
                                <option value="Other">Other (Please specify below)</option>
                            </select>
                            
                            <!-- Additional Details Text Box -->
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details (Optional)</label>
                                <textarea x-model="newAppointment.additional_notes" rows="2" 
                                          placeholder="Any additional symptoms, medical history, or specific concerns..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                            </div>
                            
                            <!-- Emergency Alert -->
                            <div x-show="newAppointment.reason && newAppointment.reason.includes('EMERGENCY')" 
                                 class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-red-800 font-semibold">EMERGENCY CASE DETECTED</span>
                                </div>
                                <p class="text-red-700 text-sm mt-1">This will be prioritized and the doctor will be notified immediately.</p>
                            </div>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg border">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                Payment Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee</label>
                                    <div class="text-2xl font-bold text-green-600">₹<span x-text="newAppointment.consultation_fee">0</span></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                    <select x-model="newAppointment.payment_method" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Payment</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="online">Online Payment</option>
                                        <option value="insurance">Insurance</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                                    <select x-model="newAppointment.payment_status" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
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

    <!-- Emergency Modal -->
    <div x-show="showEmergencyModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showEmergencyModal = false">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto border-l-4 border-red-500">
            <div class="p-6 border-b border-gray-200 bg-red-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-red-800 flex items-center">
                        <i class="fas fa-ambulance mr-2"></i>
                        🚨 Emergency Case Registration
                    </h3>
                    <button @click="showEmergencyModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-red-700 text-sm mt-2">For life-threatening conditions requiring immediate medical attention</p>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="createEmergencyCase()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Patient Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name *</label>
                            <input type="text" x-model="emergencyCase.patient_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" x-model="emergencyCase.phone" required placeholder="+1234567890"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                            <input type="tel" x-model="emergencyCase.emergency_contact" placeholder="Family member phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Doctor *</label>
                            <select x-model="emergencyCase.doctor_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Select Emergency Doctor</option>
                                <option value="4">Dr. Emergency - ER Specialist</option>
                                <option value="5">Dr. Trauma - Trauma Surgeon</option>
                                <option value="6">Dr. Critical - ICU Specialist</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Condition *</label>
                            <select x-model="emergencyCase.condition" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Select emergency condition</option>
                                <option value="Cardiac Arrest">🫀 Cardiac Arrest</option>
                                <option value="Severe Chest Pain">💔 Severe Chest Pain</option>
                                <option value="Difficulty Breathing">🫁 Difficulty Breathing</option>
                                <option value="Severe Head Injury">🧠 Severe Head Injury</option>
                                <option value="Major Bleeding">🩸 Major Bleeding</option>
                                <option value="Unconscious/Fainting">😵 Unconscious/Fainting</option>
                                <option value="Severe Burns">🔥 Severe Burns</option>
                                <option value="Poisoning">☠️ Poisoning</option>
                                <option value="Stroke Symptoms">🧠 Stroke Symptoms</option>
                                <option value="Severe Allergic Reaction">⚠️ Severe Allergic Reaction</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Details</label>
                            <textarea x-model="emergencyCase.details" rows="3" required
                                      placeholder="Describe the emergency situation, symptoms, how it happened..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                        </div>
                        
                        <!-- Emergency Notice -->
                        <div class="md:col-span-2 bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-red-800 mb-2">Emergency Case Protocol</h4>
                                    <ul class="text-red-700 text-sm space-y-1">
                                        <li>• <strong>Payment:</strong> Treatment first, billing later</li>
                                        <li>• <strong>Priority:</strong> Immediate attention, no waiting</li>
                                        <li>• <strong>Doctor:</strong> Emergency specialist will be assigned</li>
                                        <li>• <strong>Location:</strong> Patient will be directed to Emergency Room</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showEmergencyModal = false"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2">
                            <i class="fas fa-ambulance"></i>
                            <span>Register Emergency Case</span>
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
        showEmergencyModal: false,
        loading: false,
        emergencyCase: {
            patient_name: '',
            phone: '',
            emergency_contact: '',
            doctor_id: '',
            condition: '',
            details: ''
        },
        newAppointment: {
            patient_name: '',
            phone: '',
            email: '',
            doctor_id: '',
            date: '',
            time: '',
            reason: '',
            additional_notes: '',
            consultation_fee: 0,
            payment_method: '',
            payment_status: 'paid'
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
                    this.showToast('Status updated successfully!', 'success');
                } else {
                    this.showToast('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error updating appointment:', error);
                this.showToast('Network error occurred', 'error');
            }
        },
        
        async deleteAppointment(id) {
            if (!confirm('Delete this appointment?')) return;
            
            try {
                const response = await fetch('../api/appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id })
                });
                
                const data = await response.json();
                if (data.success) {
                    await this.loadAppointments();
                    this.showToast('Appointment deleted', 'success');
                } else {
                    this.showToast('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error deleting appointment:', error);
                this.showToast('Network error occurred', 'error');
            }
        },
        
        updateConsultationFee() {
            const doctorFees = {
                '1': 500,  // Dr. Smith - General Medicine
                '2': 800,  // Dr. Johnson - Cardiology  
                '3': 1000  // Dr. Brown - Neurology
            };
            
            this.newAppointment.consultation_fee = doctorFees[this.newAppointment.doctor_id] || 0;
            // Reset reason when doctor changes
            this.newAppointment.reason = '';
        },
        
        handleReasonChange() {
            // Check if emergency case is selected
            if (this.newAppointment.reason && this.newAppointment.reason.includes('EMERGENCY')) {
                // Auto-set payment status to paid for emergencies
                this.newAppointment.payment_status = 'paid';
                // Show emergency alert
                this.showToast('🚨 Emergency case detected! This will be prioritized.', 'error');
            }
        },
        
        showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.textContent = message;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        },
        
        async createNewAppointment() {
            try {
                if (!this.newAppointment.patient_name || !this.newAppointment.phone || 
                    !this.newAppointment.doctor_id || !this.newAppointment.date || 
                    !this.newAppointment.time || !this.newAppointment.payment_method) {
                    this.showToast('Please fill all required fields', 'error');
                    return;
                }
                
                // Get or create patient ID
                const patientResponse = await fetch('../api/patients.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'get_or_create',
                        name: this.newAppointment.patient_name,
                        phone: this.newAppointment.phone,
                        email: this.newAppointment.email || null
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
                    // Show toast notification first
                    this.showToast('✅ Appointment created successfully!', 'success');
                    
                    // Close modal
                    this.showNewAppointmentModal = false;
                    
                    // Reset form
                    this.newAppointment = {
                        patient_name: '',
                        phone: '',
                        email: '',
                        doctor_id: '',
                        date: '',
                        time: '',
                        reason: '',
                        additional_notes: '',
                        consultation_fee: 0,
                        payment_method: '',
                        payment_status: 'paid'
                    };
                    
                    // Reload appointments to update stats and table
                    await this.loadAppointments();
                    
                    console.log('✅ Appointments reloaded. Total:', this.appointments.length);
                } else {
                    this.showToast('Error: ' + (data.message || 'Failed to create appointment'), 'error');
                }
                
            } catch (error) {
                console.error('Error creating appointment:', error);
                this.showToast('Network error: ' + error.message, 'error');
            }
        },
        
        async createEmergencyCase() {
            try {
                if (!this.emergencyCase.patient_name || !this.emergencyCase.phone || 
                    !this.emergencyCase.doctor_id || !this.emergencyCase.condition || 
                    !this.emergencyCase.details) {
                    this.showToast('Please fill all required fields', 'error');
                    return;
                }
                
                // Get or create patient ID
                const patientResponse = await fetch('../api/patients.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'get_or_create',
                        name: this.emergencyCase.patient_name,
                        phone: this.emergencyCase.phone,
                        emergency_contact: this.emergencyCase.emergency_contact
                    })
                });
                
                const patientData = await patientResponse.json();
                const patientId = patientData.patient_id || 1;
                
                // Create emergency appointment with immediate scheduling
                const response = await fetch('../api/appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'create',
                        patient_id: patientId,
                        doctor_id: this.emergencyCase.doctor_id,
                        department_id: 1, // Emergency department
                        appointment_date: new Date().toISOString().split('T')[0],
                        appointment_time: new Date().toTimeString().split(' ')[0],
                        reason: `🚨 EMERGENCY: ${this.emergencyCase.condition}`,
                        notes: `Emergency Details: ${this.emergencyCase.details}\nEmergency Contact: ${this.emergencyCase.emergency_contact}`
                    })
                });
                
                const data = await response.json();
                console.log('Emergency case response:', data);
                
                if (data.success) {
                    // Show toast notification first
                    this.showToast('🚨 Emergency case registered successfully!', 'success');
                    
                    // Close modal
                    this.showEmergencyModal = false;
                    
                    // Reset form
                    this.emergencyCase = {
                        patient_name: '',
                        phone: '',
                        emergency_contact: '',
                        doctor_id: '',
                        condition: '',
                        details: ''
                    };
                    
                    // Reload appointments to update stats and table
                    await this.loadAppointments();
                    
                    console.log('✅ Emergency case created and appointments reloaded');
                } else {
                    this.showToast('Error: ' + (data.message || 'Failed to create emergency case'), 'error');
                }
                
            } catch (error) {
                console.error('Error creating emergency case:', error);
                this.showToast('Network error: ' + error.message, 'error');
            }
        }
    }
}
</script>
