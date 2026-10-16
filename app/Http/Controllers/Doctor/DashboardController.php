<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\LabRequest;
use App\Models\Prescription;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403, 'Doctor profile not found.');
        }

        $stats = [
            'today_appointments'  => Appointment::where('doctor_id', $doctor->id)
                                        ->whereDate('appointment_date', today())
                                        ->count(),
            'total_patients'      => Appointment::where('doctor_id', $doctor->id)
                                        ->distinct('patient_id')
                                        ->count('patient_id'),
            'pending_lab_requests'=> LabRequest::where('doctor_id', $doctor->id)
                                        ->whereIn('status', ['pending', 'collected', 'processing'])
                                        ->count(),
            'pending_prescriptions'=> Prescription::where('doctor_id', $doctor->id)
                                        ->where('status', 'pending')
                                        ->count(),
        ];

        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentPatients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->latest()
            ->take(5)
            ->get();

        $recentLabRequests = LabRequest::with(['patient', 'tests'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('doctor', 'stats', 'todayAppointments', 'recentPatients', 'recentLabRequests'));
    }
}
