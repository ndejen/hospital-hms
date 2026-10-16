@extends('layouts.app')

@section('title', 'Laboratory Dashboard')
@section('page-title', 'Laboratory Dashboard')

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-tasks stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_requests'] }}</p>
            <p class="stat-label">Pending Requests</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-cogs stat-icon"></i>
            <p class="stat-number">{{ $stats['processing'] }}</p>
            <p class="stat-label">Processing</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-vials stat-icon"></i>
            <p class="stat-number">{{ $stats['completed_today'] }}</p>
            <p class="stat-label">Completed Today</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-flask stat-icon"></i>
            <p class="stat-number">{{ $stats['total_tests'] }}</p>
            <p class="stat-label">Total Tests</p>
        </div>
    </div>
</div>

{{-- Pending Requests --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-tasks me-2"></i>Pending Requests (Priority Order)</span>
        <a href="{{ route('laboratory.tests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Priority</th><th>Patient</th><th>Request #</th><th>Tests</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($pendingRequests as $req)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $req->priority === 'urgent' ? 'danger' : ($req->priority === 'stat' ? 'warning' : 'info') }}">
                                {{ strtoupper($req->priority) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-500" style="font-size:13px">{{ $req->patient->full_name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#6c757d">{{ $req->patient->patient_number ?? '' }}</div>
                        </td>
                        <td style="font-size:13px">{{ $req->request_number }}</td>
                        <td style="font-size:13px">{{ $req->tests->count() }} tests</td>
                        <td>
                            <a href="{{ route('laboratory.tests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No pending requests</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Results --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-vials me-2"></i>Recent Results</span>
                <a href="{{ route('laboratory.results.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Test</th><th>Patient</th><th>Result</th><th>Interpretation</th></tr></thead>
                    <tbody>
                        @forelse($recentResults as $res)
                        <tr>
                            <td style="font-size:13px">{{ $res->test->name ?? 'N/A' }}</td>
                            <td style="font-size:13px">{{ $res->labRequest->patient->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="fw-500" style="font-size:13px">{{ $res->result_value ?? 'Pending' }}</span>
                                <div style="font-size:11px;color:#6c757d">{{ $res->unit ?? '' }}</div>
                            </td>
                            <td>
                                @php
                                $interpretationColor = [
                                    'normal' => 'success',
                                    'low' => 'warning',
                                    'high' => 'danger',
                                    'critical' => 'danger',
                                ][$res->interpretation] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $interpretationColor }}">
                                    {{ ucfirst($res->interpretation) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No recent results</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('laboratory.results.create') }}" class="btn btn-primary w-100 btn-sm">
                            <i class="fas fa-plus me-1"></i>Record Result
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('laboratory.tests.index') }}" class="btn btn-success w-100 btn-sm">
                            <i class="fas fa-list me-1"></i>All Requests
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-info w-100 btn-sm">
                            <i class="fas fa-print me-1"></i>Print Report
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-warning w-100 btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>Statistics
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-chart-pie me-2"></i>Workload Summary</div>
            <div class="card-body">
                <div style="font-size:13px">
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Urgent requests:</span> <span>{{ $pendingRequests->where('priority', 'urgent')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Stat requests:</span> <span>{{ $pendingRequests->where('priority', 'stat')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Routine requests:</span> <span>{{ $pendingRequests->where('priority', 'routine')->count() }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between py-1">
                        <span>Total pending:</span> <strong>{{ $pendingRequests->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
