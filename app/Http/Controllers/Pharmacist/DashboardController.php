<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Prescription;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_prescriptions' => Prescription::where('status', 'pending')->count(),
            'dispensed_today'       => Prescription::where('status', 'dispensed')->whereDate('updated_at', today())->count(),
            'low_stock'             => Medicine::whereRaw('stock_quantity <= reorder_level')->count(),
            'expiring_soon'         => Medicine::whereBetween('expiry_date', [today(), today()->addDays(30)])->count(),
        ];

        $pendingPrescriptions = Prescription::with(['patient', 'doctor.user', 'items.medicine'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $lowStockMedicines = Medicine::whereRaw('stock_quantity <= reorder_level')
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        return view('pharmacist.dashboard', compact('stats', 'pendingPrescriptions', 'lowStockMedicines'));
    }
}
