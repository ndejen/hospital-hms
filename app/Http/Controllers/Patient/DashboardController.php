<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        if (!$patient) {
            return view('patient.dashboard', ['patient' => null, 'stats' => [], 'appointments' => collect()]);
        }

        $patient->load(['appointments.doctor.user', 'prescriptions.items', 'invoices']);

        $stats = [
            'upcoming_appointments' => $patient->appointments()
                ->where('appointment_date', '>=', today())
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count(),
            'total_visits' => $patient->appointments()->where('status', 'completed')->count(),
            'active_prescriptions' => $patient->prescriptions()->where('status', 'pending')->count(),
            'pending_bills' => $patient->invoices()->whereIn('status', ['draft', 'sent', 'partial'])->sum('balance_due'),
        ];

        $upcomingAppointments = $patient->appointments()
            ->with('doctor.user')
            ->where('appointment_date', '>=', today())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('patient', 'stats', 'upcomingAppointments'));
    }
}
