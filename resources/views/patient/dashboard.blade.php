@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
@if(!$patient)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Your patient profile is not linked. Please contact the administration.
</div>
@else
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-calendar-check stat-icon"></i>
            <p class="stat-number">{{ $stats['upcoming_appointments'] }}</p>
            <p class="stat-label">Upcoming Appointments</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-history stat-icon"></i>
            <p class="stat-number">{{ $stats['total_visits'] }}</p>
            <p class="stat-label">Total Visits</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-pills stat-icon"></i>
            <p class="stat-number">{{ $stats['active_prescriptions'] }}</p>
            <p class="stat-label">Active Prescriptions</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-file-invoice-dollar stat-icon"></i>
            <p class="stat-number">{{ $currencySymbol ?? '$' }}{{ number_format($stats['pending_bills'], 2) }}</p>
            <p class="stat-label">Pending Bills</p>
        </div>
    </div>
</div>

{{-- Profile Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-user me-2"></i>My Profile</span>
                <a href="{{ route('patient.profile.edit') }}" class="btn btn-sm btn-outline-primary">Edit</a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="avatar avatar-lg" style="background:linear-gradient(135deg,#0d6efd,#6f42c1)">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $patient->full_name }}</h5>
                        <div class="text-muted" style="font-size:13px">{{ $patient->patient_number }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:12px">Age / Gender</div>
                        <div style="font-size:13px">{{ $patient->age }} years, {{ ucfirst($patient->gender) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:12px">Blood Group</div>
                        <div style="font-size:13px">{{ $patient->blood_group ?? 'Not recorded' }}</div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="text-muted" style="font-size:12px">Phone</div>
                        <div style="font-size:13px">{{ $patient->phone ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments</span>
                <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Date</th><th>Doctor</th><th>Time</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($upcomingAppointments as $appt)
                        <tr>
                            <td style="font-size:13px">{{ $appt->appointment_date->format('d M Y') }}</td>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $appt->doctor->full_name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $appt->doctor->specialization ?? '' }}</div>
                            </td>
                            <td style="font-size:13px">{{ $appt->appointment_time }}</td>
                            <td>{!! $appt->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No upcoming appointments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Medical Summary --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-notes-medical me-2"></i>My Medical Summary</span>
        <a href="{{ route('patient.medical-history.index') }}" class="btn btn-sm btn-outline-primary">View Full History</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-muted" style="font-size:12px">Known Allergies</div>
                <div class="mt-1">{{ $patient->known_allergies ?? 'None recorded' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted" style="font-size:12px">Chronic Diseases</div>
                <div class="mt-1">{{ $patient->chronic_diseases ?? 'None recorded' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted" style="font-size:12px">Last Visit</div>
                <div class="mt-1">{{ $patient->appointments->where('status', 'completed')->last()?->appointment_date->format('d M Y') ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card">
    <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3 col-6">
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary w-100 btn-sm">
                    <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('patient.medical-history.index') }}" class="btn btn-success w-100 btn-sm">
                    <i class="fas fa-history me-1"></i>Medical History
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('patient.profile.edit') }}" class="btn btn-info w-100 btn-sm">
                    <i class="fas fa-user-edit me-1"></i>Update Profile
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#" class="btn btn-warning w-100 btn-sm">
                    <i class="fas fa-file-invoice me-1"></i>View Bills
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
