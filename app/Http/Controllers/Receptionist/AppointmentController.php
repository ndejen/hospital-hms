<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user']);
        if ($request->filled('date'))   $query->whereDate('appointment_date', $request->date);
        if ($request->filled('status')) $query->where('status', $request->status);
        $appointments = $query->orderBy('appointment_date', 'desc')->orderBy('appointment_time')->paginate(15)->withQueryString();
        return view('receptionist.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients    = Patient::where('is_active', true)->get();
        $doctors     = Doctor::with('user')->where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        return view('receptionist.appointments.create', compact('patients', 'doctors', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'department_id'    => 'nullable|exists:departments,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'type'             => 'required|in:new,follow_up,emergency',
            'reason'           => 'nullable|string',
        ]);
        $validated['appointment_number'] = 'APT-' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['status'] = 'scheduled';
        $appointment = Appointment::create($validated);
        return redirect()->route('receptionist.appointments.show', $appointment)->with('success', 'Appointment booked.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'department']);
        return view('receptionist.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::all(); $doctors = Doctor::with('user')->get();
        return view('receptionist.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status'           => 'required',
        ]));
        return back()->with('success', 'Updated.');
    }
    public function destroy(Appointment $appointment) { $appointment->delete(); return redirect()->route('receptionist.appointments.index'); }

    public function checkIn(Request $request, Appointment $appointment)
    {
        $appointment->update(['status' => 'checked_in']);
        return back()->with('success', 'Patient checked in.');
    }

    public function checkOut(Request $request, Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Patient checked out.');
    }
}
