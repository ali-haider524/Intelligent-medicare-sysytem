<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isPatient()) {
            $appointments = $user->patientAppointments()
                ->with(['doctor', 'department'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        } elseif ($user->isDoctor()) {
            $appointments = $user->doctorAppointments()
                ->with(['patient', 'department'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        } else {
            $appointments = Appointment::with(['patient', 'doctor', 'department'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $doctors = DoctorProfile::with(['user', 'department'])->available()->get();

        return view('appointments.create', compact('departments', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'is_emergency' => 'boolean',
        ]);

        $doctor = \App\Models\User::findOrFail($request->doctor_id);
        $doctorProfile = $doctor->doctorProfile;

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'department_id' => $doctorProfile->department_id,
            'appointment_date' => $request->appointment_date,
            'symptoms' => $request->symptoms,
            'notes' => $request->notes,
            'consultation_fee' => $doctorProfile->consultation_fee,
            'is_emergency' => $request->boolean('is_emergency'),
            'booking_type' => 'online',
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully!');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        $appointment->load(['patient', 'doctor', 'department', 'medicalRecord']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $departments = Department::active()->get();
        $doctors = DoctorProfile::with(['user', 'department'])->available()->get();

        return view('appointments.edit', compact('appointment', 'departments', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $request->validate([
            'appointment_date' => 'required|date|after:now',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'status' => 'in:scheduled,confirmed,cancelled',
        ]);

        $appointment->update($request->only([
            'appointment_date', 'symptoms', 'notes', 'status'
        ]));

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after:today',
        ]);

        $doctor = \App\Models\User::findOrFail($request->doctor_id);
        $doctorProfile = $doctor->doctorProfile;

        if (!$doctorProfile) {
            return response()->json(['error' => 'Doctor profile not found'], 404);
        }

        $date = \Carbon\Carbon::parse($request->date);
        $availableSlots = $doctorProfile->getAvailableSlots($date);

        // Remove already booked slots
        $bookedSlots = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->pluck('appointment_date')
            ->map(function($datetime) {
                return \Carbon\Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        $availableSlots = array_diff($availableSlots, $bookedSlots);

        return response()->json([
            'available_slots' => array_values($availableSlots),
            'doctor_name' => $doctor->name,
            'consultation_fee' => $doctorProfile->consultation_fee,
        ]);
    }

    public function todayAppointments()
    {
        $user = Auth::user();
        
        if ($user->isDoctor()) {
            $appointments = $user->doctorAppointments()
                ->with(['patient'])
                ->today()
                ->orderBy('appointment_date')
                ->get();
        } else {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->today()
                ->orderBy('appointment_date')
                ->get();
        }

        return view('appointments.today', compact('appointments'));
    }
}