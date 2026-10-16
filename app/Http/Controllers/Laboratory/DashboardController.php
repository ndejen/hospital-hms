<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\LabResult;
use App\Models\LaboratoryTest;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_requests'  => LabRequest::where('status', 'pending')->count(),
            'processing'        => LabRequest::where('status', 'processing')->count(),
            'completed_today'   => LabRequest::where('status', 'completed')->whereDate('updated_at', today())->count(),
            'total_tests'       => LaboratoryTest::count(),
        ];

        $pendingRequests = LabRequest::with(['patient', 'doctor.user', 'tests'])
            ->whereIn('status', ['pending', 'collected'])
            ->orderByRaw("FIELD(priority, 'stat', 'urgent', 'routine')")
            ->take(10)
            ->get();

        $recentResults = LabResult::with(['labRequest.patient', 'test'])
            ->latest()
            ->take(5)
            ->get();

        return view('laboratory.dashboard', compact('stats', 'pendingRequests', 'recentResults'));
    }
}
