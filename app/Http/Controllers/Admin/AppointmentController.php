<?php

namespace App\Http\Controllers\Admin;

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
        $query = Appointment::with(['patient', 'doctor.user', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%")
            )->orWhere('appointment_number', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time')
                              ->paginate(15)
                              ->withQueryString();

        $doctors = Doctor::with('user')->where('is_active', true)->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $patients    = Patient::where('is_active', true)->get();
        $doctors     = Doctor::with(['user', 'department'])->where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        return view('admin.appointments.create', compact('patients', 'doctors', 'departments'));
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
            'notes'            => 'nullable|string',
        ]);

        $validated['appointment_number'] = 'APT-' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['status'] = 'scheduled';

        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'department', 'medicalRecord', 'labRequest', 'invoice']);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients    = Patient::where('is_active', true)->get();
        $doctors     = Doctor::with('user')->where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'departments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'department_id'    => 'nullable|exists:departments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'type'             => 'required|in:new,follow_up,emergency',
            'status'           => 'required|in:scheduled,confirmed,checked_in,completed,cancelled,no_show',
            'reason'           => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:scheduled,confirmed,checked_in,completed,cancelled,no_show']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }
}
