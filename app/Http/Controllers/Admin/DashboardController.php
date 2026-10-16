<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Admission;
use App\Models\LabRequest;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'     => Patient::count(),
            'total_doctors'      => Doctor::count(),
            'total_nurses'       => Nurse::count(),
            'total_users'        => User::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
            'current_admissions' => Admission::where('status', 'admitted')->count(),
            'total_revenue'      => Invoice::where('status', 'paid')->sum('total'),
            'pending_invoices'   => Invoice::whereIn('status', ['draft', 'sent', 'partial'])->count(),
            'pending_lab_requests' => LabRequest::whereIn('status', ['pending', 'collected', 'processing'])->count(),
            'pending_prescriptions' => Prescription::where('status', 'pending')->count(),
        ];

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->where('invoice_date', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(invoice_date) as month, YEAR(invoice_date) as year, SUM(total) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Appointments by status for chart
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(10)
            ->get();

        // Recent admissions
        $recentAdmissions = Admission::with(['patient', 'doctor', 'ward'])
            ->where('status', 'admitted')
            ->latest('admission_date')
            ->take(5)
            ->get();

        // Patients registered per month (last 6 months)
        $monthlyPatients = Patient::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'monthlyRevenue', 'appointmentsByStatus',
            'recentAppointments', 'recentAdmissions', 'monthlyPatients'
        ));
    }
}
