<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Patient;
use App\Models\VitalSign;
use App\Models\NursingNote;

class DashboardController extends Controller
{
    public function index()
    {
        $nurse = auth()->user()->nurse;

        $stats = [
            'total_patients'    => Patient::count(),
            'admitted_patients' => Admission::where('status', 'admitted')->count(),
            'vitals_today'      => VitalSign::whereDate('recorded_at', today())->count(),
            'notes_today'       => NursingNote::whereDate('noted_at', today())->count(),
        ];

        $currentAdmissions = Admission::with(['patient', 'ward', 'bed', 'doctor.user'])
            ->where('status', 'admitted')
            ->latest('admission_date')
            ->take(10)
            ->get();

        $recentVitals = VitalSign::with(['patient'])
            ->whereDate('recorded_at', today())
            ->latest('recorded_at')
            ->take(5)
            ->get();

        return view('nurse.dashboard', compact('nurse', 'stats', 'currentAdmissions', 'recentVitals'));
    }
}
