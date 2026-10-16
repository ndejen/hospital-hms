<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $query  = Appointment::with('patient')->where('doctor_id', $doctor->id);

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time')
                              ->paginate(15)
                              ->withQueryString();

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function create()  { return view('doctor.appointments.create'); }
    public function store(Request $request) { return back(); }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'medicalRecord', 'vitalSigns', 'labRequest.tests', 'invoice']);
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment) { return view('doctor.appointments.edit', compact('appointment')); }
    public function update(Request $request, Appointment $appointment) { return back(); }
    public function destroy(Appointment $appointment) { return back(); }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:scheduled,confirmed,checked_in,completed,cancelled,no_show']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }
}
