@extends('layouts.app')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Doctor Dashboard')

@section('content')
{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-calendar-check stat-icon"></i>
            <p class="stat-number">{{ $stats['today_appointments'] }}</p>
            <p class="stat-label">Today's Appointments</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-procedures stat-icon"></i>
            <p class="stat-number">{{ $stats['total_patients'] }}</p>
            <p class="stat-label">Total Patients</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-flask stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_lab_requests'] }}</p>
            <p class="stat-label">Pending Lab Tests</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-prescription-bottle-alt stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_prescriptions'] }}</p>
            <p class="stat-label">Pending Prescriptions</p>
        </div>
    </div>
</div>

{{-- Today's Appointments --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-calendar-day me-2"></i>Today's Appointments</span>
        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr><th>Time</th><th>Patient</th><th>Reason</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($todayAppointments as $appt)
                <tr>
                    <td><strong>{{ $appt->appointment_time }}</strong></td>
                    <td>
                        <div class="fw-500">{{ $appt->patient->full_name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:#6c757d">Age {{ $appt->patient->age ?? 'N/A' }}, {{ ucfirst($appt->patient->gender ?? '') }}</div>
                    </td>
                    <td style="font-size:13px">{{ $appt->reason ?? 'Routine check-up' }}</td>
                    <td>{!! $appt->status_badge !!}</td>
                    <td>
                        <a href="{{ route('doctor.appointments.show', $appt) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No appointments scheduled for today</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Patients --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-procedures me-2"></i>Recent Patients</span>
                <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Patient</th><th>Age</th><th>Last Visit</th></tr></thead>
                    <tbody>
                        @forelse($recentPatients as $p)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $p->full_name }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $p->patient_number }}</div>
                            </td>
                            <td>{{ $p->age }} yrs</td>
                            <td>{{ $p->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No patients</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Lab Requests --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-flask me-2"></i>Recent Lab Requests</span>
                <a href="{{ route('doctor.lab-requests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Patient</th><th>Tests</th><th>Priority</th></tr></thead>
                    <tbody>
                        @forelse($recentLabRequests as $req)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $req->patient->full_name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $req->request_number }}</div>
                            </td>
                            <td style="font-size:12px">{{ $req->tests->count() }} tests</td>
                            <td>
                                <span class="badge bg-{{ $req->priority === 'urgent' ? 'danger' : ($req->priority === 'stat' ? 'warning' : 'info') }}">
                                    {{ ucfirst($req->priority) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No lab requests</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card mt-4">
    <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3 col-6">
                <a href="{{ route('doctor.appointments.create') }}" class="btn btn-outline-primary w-100 btn-sm">
                    <i class="fas fa-calendar-plus me-1"></i>New Appointment
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-outline-success w-100 btn-sm">
                    <i class="fas fa-prescription me-1"></i>New Prescription
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('doctor.lab-requests.create') }}" class="btn btn-outline-warning w-100 btn-sm">
                    <i class="fas fa-flask me-1"></i>Lab Request
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('doctor.diagnoses.create') }}" class="btn btn-outline-info w-100 btn-sm">
                    <i class="fas fa-stethoscope me-1"></i>New Diagnosis
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
