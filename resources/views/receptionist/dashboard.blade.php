@extends('layouts.app')

@section('title', 'Receptionist Dashboard')
@section('page-title', 'Receptionist Dashboard')

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-calendar-day stat-icon"></i>
            <p class="stat-number">{{ $stats['today_appointments'] }}</p>
            <p class="stat-label">Today's Appointments</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-check-circle stat-icon"></i>
            <p class="stat-number">{{ $stats['checked_in'] }}</p>
            <p class="stat-label">Checked In</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-procedures stat-icon"></i>
            <p class="stat-number">{{ $stats['total_patients'] }}</p>
            <p class="stat-label">Total Patients</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-file-invoice stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_invoices'] }}</p>
            <p class="stat-label">Pending Invoices</p>
        </div>
    </div>
</div>

{{-- Today's Appointments --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-calendar-check me-2"></i>Today's Appointments</span>
        <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-sm btn-outline-primary">New Appointment</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($todayAppointments as $appt)
                    <tr>
                        <td><strong>{{ $appt->appointment_time }}</strong></td>
                        <td>
                            <div class="fw-500" style="font-size:13px">{{ $appt->patient->full_name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#6c757d">{{ $appt->patient->patient_number ?? '' }}</div>
                        </td>
                        <td style="font-size:13px">{{ $appt->doctor->full_name ?? 'N/A' }}</td>
                        <td>{!! $appt->status_badge !!}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('receptionist.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($appt->status === 'confirmed')
                                <form method="POST" action="{{ route('receptionist.appointments.checkin', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" title="Check In">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                </form>
                                @endif
                                @if($appt->status === 'checked_in')
                                <form method="POST" action="{{ route('receptionist.appointments.checkout', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-warning" title="Check Out">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No appointments scheduled for today</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Patients --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-user-plus me-2"></i>Recently Registered Patients</span>
                <a href="{{ route('receptionist.patients.create') }}" class="btn btn-sm btn-outline-primary">New Patient</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Patient</th><th>Phone</th><th>Registered</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($recentPatients as $p)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $p->full_name }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $p->patient_number }}</div>
                            </td>
                            <td style="font-size:13px">{{ $p->phone ?? '—' }}</td>
                            <td style="font-size:13px">{{ $p->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('receptionist.patients.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No patients</td></tr>
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
                        <a href="{{ route('receptionist.patients.create') }}" class="btn btn-primary w-100 btn-sm">
                            <i class="fas fa-user-plus me-1"></i>Register Patient
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-success w-100 btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('receptionist.patients.index') }}" class="btn btn-info w-100 btn-sm">
                            <i class="fas fa-search me-1"></i>Find Patient
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('receptionist.billing.index') }}" class="btn btn-warning w-100 btn-sm">
                            <i class="fas fa-file-invoice me-1"></i>View Invoices
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary w-100 btn-sm">
                            <i class="fas fa-calendar-alt me-1"></i>All Appointments
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-danger w-100 btn-sm">
                            <i class="fas fa-print me-1"></i>Print Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
