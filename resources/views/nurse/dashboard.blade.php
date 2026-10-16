@extends('layouts.app')

@section('title', 'Nurse Dashboard')
@section('page-title', 'Nurse Dashboard')

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-procedures stat-icon"></i>
            <p class="stat-number">{{ $stats['total_patients'] }}</p>
            <p class="stat-label">Total Patients</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-bed stat-icon"></i>
            <p class="stat-number">{{ $stats['admitted_patients'] }}</p>
            <p class="stat-label">Admitted Patients</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-heartbeat stat-icon"></i>
            <p class="stat-number">{{ $stats['vitals_today'] }}</p>
            <p class="stat-label">Vitals Today</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-notes-medical stat-icon"></i>
            <p class="stat-number">{{ $stats['notes_today'] }}</p>
            <p class="stat-label">Notes Today</p>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Current Admissions --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-bed me-2"></i>Current Admissions</span>
                <a href="{{ route('nurse.admissions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Patient</th><th>Ward</th><th>Bed</th><th>Doctor</th><th>Days</th></tr></thead>
                        <tbody>
                            @forelse($currentAdmissions as $adm)
                            <tr>
                                <td>
                                    <div class="fw-500" style="font-size:13px">{{ $adm->patient->full_name ?? 'N/A' }}</div>
                                    <div style="font-size:11px;color:#6c757d">{{ $adm->admission_number }}</div>
                                </td>
                                <td style="font-size:13px">{{ $adm->ward->name ?? 'N/A' }}</td>
                                <td style="font-size:13px">{{ $adm->bed->bed_number ?? 'N/A' }}</td>
                                <td style="font-size:13px">{{ $adm->doctor->full_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $adm->length_of_stay }}d</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No current admissions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Vitals --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-heartbeat me-2"></i>Recent Vital Signs</span>
                <a href="{{ route('nurse.vital-signs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Patient</th><th>BP</th><th>Temp</th><th>Pulse</th></tr></thead>
                    <tbody>
                        @forelse($recentVitals as $vital)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $vital->patient->full_name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $vital->recorded_at->format('H:i') }}</div>
                            </td>
                            <td><span class="badge bg-{{ $vital->blood_pressure && Str::contains($vital->blood_pressure, '120/80') ? 'success' : 'warning' }}">{{ $vital->blood_pressure ?? '—' }}</span></td>
                            <td style="font-size:13px">{{ $vital->temperature ?? '—' }}°C</td>
                            <td style="font-size:13px">{{ $vital->pulse_rate ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No vital signs today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('nurse.vital-signs.create') }}" class="btn btn-outline-primary w-100 btn-sm">
                            <i class="fas fa-heartbeat me-1"></i>Record Vitals
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('nurse.nurse-notes.create') }}" class="btn btn-outline-success w-100 btn-sm">
                            <i class="fas fa-notes-medical me-1"></i>Add Note
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('nurse.patients.index') }}" class="btn btn-outline-info w-100 btn-sm">
                            <i class="fas fa-procedures me-1"></i>View Patients
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('nurse.admissions.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                            <i class="fas fa-bed me-1"></i>Admissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
