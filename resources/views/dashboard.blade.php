<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @if(auth()->user()->isPatient())
                <!-- Patient Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-4m4-4h8m-4-4v8m-4 4h8"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Upcoming Appointments
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ auth()->user()->patientAppointments()->upcoming()->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View all appointments →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Book New Appointment
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        Quick Booking
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.create') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Book appointment →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Medical Records
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ auth()->user()->medicalRecords()->count() }} Records
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                View records →
                            </a>
                        </div>
                    </div>
                </div>

            @elseif(auth()->user()->isDoctor())
                <!-- Doctor Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Today's Appointments
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ auth()->user()->doctorAppointments()->today()->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.today') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View today's schedule →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Patients
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ auth()->user()->doctorAppointments()->distinct('patient_id')->count('patient_id') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                View all appointments →
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <!-- Admin Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Patients
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ \App\Models\User::patients()->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Doctors
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ \App\Models\User::doctors()->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-4m4-4h8m-4-4v8m-4 4h8"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Today's Appointments
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ \App\Models\Appointment::today()->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                    
                    @if(auth()->user()->isPatient())
                        @php
                            $recentAppointments = auth()->user()->patientAppointments()
                                ->with(['doctor', 'department'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @forelse($recentAppointments as $appointment)
                            <div class="flex items-center py-3 border-b border-gray-200 last:border-b-0">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        Appointment with Dr. {{ $appointment->doctor->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $appointment->department->name }} • {{ $appointment->appointment_date->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No recent appointments</p>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>