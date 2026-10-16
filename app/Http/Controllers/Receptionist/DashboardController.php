<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'today_appointments'  => Appointment::whereDate('appointment_date', today())->count(),
            'scheduled'           => Appointment::whereDate('appointment_date', today())->where('status', 'scheduled')->count(),
            'checked_in'          => Appointment::whereDate('appointment_date', today())->where('status', 'checked_in')->count(),
            'total_patients'      => Patient::count(),
            'pending_invoices'    => Invoice::whereIn('status', ['draft', 'sent'])->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentPatients = Patient::latest()->take(5)->get();

        return view('receptionist.dashboard', compact('stats', 'todayAppointments', 'recentPatients'));
    }
}
