<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient      = auth()->user()->patient;
        $appointments = $patient->appointments()->with('doctor.user')->latest('appointment_date')->paginate(15);
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors     = Doctor::with(['user', 'department'])->where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        return view('patient.appointments.create', compact('doctors', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'        => 'required|exists:doctors,id',
            'department_id'    => 'nullable|exists:departments,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'type'             => 'required|in:new,follow_up,emergency',
            'reason'           => 'nullable|string',
        ]);

        $patient = auth()->user()->patient;

        $validated['patient_id']         = $patient->id;
        $validated['appointment_number'] = 'APT-' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['status']             = 'scheduled';

        $appointment = Appointment::create($validated);

        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'department', 'medicalRecord']);
        return view('patient.appointments.show', compact('appointment'));
    }
}
