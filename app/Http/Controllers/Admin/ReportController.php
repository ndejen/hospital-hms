<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\LabRequest;
use App\Models\LabResult;
use App\Models\Prescription;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function patients(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');

        $patients = Patient::whereBetween('created_at', [$from, $to . ' 23:59:59'])->get();

        $stats = [
            'total'  => $patients->count(),
            'male'   => $patients->where('gender', 'male')->count(),
            'female' => $patients->where('gender', 'female')->count(),
        ];

        $byBloodGroup = $patients->groupBy('blood_group')
            ->map->count();

        return view('admin.reports.patients', compact('patients', 'stats', 'byBloodGroup', 'from', 'to'));
    }

    public function financial(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');

        $invoices = Invoice::with('patient')
            ->whereBetween('invoice_date', [$from, $to])
            ->get();

        $payments = Payment::with('invoice')
            ->whereBetween('payment_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();

        $stats = [
            'total_billed'   => $invoices->sum('total'),
            'total_collected'=> $payments->sum('amount'),
            'outstanding'    => $invoices->sum('balance_due'),
        ];

        return view('admin.reports.financial', compact('invoices', 'payments', 'stats', 'from', 'to'));
    }

    public function laboratory(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');

        $requests = LabRequest::with(['patient', 'tests'])
            ->whereBetween('requested_date', [$from, $to])
            ->get();

        $stats = [
            'total'     => $requests->count(),
            'completed' => $requests->where('status', 'completed')->count(),
            'pending'   => $requests->whereIn('status', ['pending', 'collected', 'processing'])->count(),
        ];

        return view('admin.reports.laboratory', compact('requests', 'stats', 'from', 'to'));
    }

    public function pharmacy(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');

        $prescriptions = Prescription::with(['patient', 'doctor.user', 'items.medicine'])
            ->whereBetween('prescribed_date', [$from, $to])
            ->get();

        $stats = [
            'total'      => $prescriptions->count(),
            'dispensed'  => $prescriptions->where('status', 'dispensed')->count(),
            'pending'    => $prescriptions->where('status', 'pending')->count(),
        ];

        return view('admin.reports.pharmacy', compact('prescriptions', 'stats', 'from', 'to'));
    }
}
