@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')

{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-procedures stat-icon"></i>
            <p class="stat-number">{{ number_format($stats['total_patients']) }}</p>
            <p class="stat-label">Total Patients</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-user-md stat-icon"></i>
            <p class="stat-number">{{ $stats['total_doctors'] }}</p>
            <p class="stat-label">Doctors</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-calendar-check stat-icon"></i>
            <p class="stat-number">{{ $stats['today_appointments'] }}</p>
            <p class="stat-label">Today's Appointments</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-bed stat-icon"></i>
            <p class="stat-number">{{ $stats['current_admissions'] }}</p>
            <p class="stat-label">Current Admissions</p>
        </div>
    </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-purple">
            <i class="fas fa-user-nurse stat-icon"></i>
            <p class="stat-number">{{ $stats['total_nurses'] }}</p>
            <p class="stat-label">Nurses</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-teal">
            <i class="fas fa-file-invoice-dollar stat-icon"></i>
            <p class="stat-number">{{ $currencySymbol ?? '$' }}{{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="stat-label">Total Revenue</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-danger">
            <i class="fas fa-flask stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_lab_requests'] }}</p>
            <p class="stat-label">Pending Lab Tests</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-orange">
            <i class="fas fa-prescription-bottle-alt stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_prescriptions'] }}</p>
            <p class="stat-label">Pending Prescriptions</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-line me-2 text-primary"></i>Monthly Revenue (Last 6 Months)</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2 text-success"></i>Appointments by Status
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="appointmentsChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-calendar me-2"></i>Recent Appointments</span>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appt)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm" style="background:{{ ['#0d6efd','#198754','#fd7e14','#6f42c1','#dc3545'][rand(0,4)] }}">
                                            {{ strtoupper(substr($appt->patient->first_name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:500;font-size:13px">{{ $appt->patient->full_name ?? 'N/A' }}</div>
                                            <div style="font-size:11px;color:#6c757d">{{ $appt->patient->patient_number ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:13px">{{ $appt->doctor->full_name ?? 'N/A' }}</td>
                                <td style="font-size:13px">
                                    {{ $appt->appointment_date->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $appt->appointment_time }}</small>
                                </td>
                                <td>{!! $appt->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No appointments found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-bed me-2"></i>Current Admissions</span>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Ward</th>
                            <th>Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAdmissions as $adm)
                        <tr>
                            <td>
                                <div style="font-weight:500;font-size:13px">{{ $adm->patient->full_name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $adm->admission_number }}</div>
                            </td>
                            <td style="font-size:13px">{{ $adm->ward->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info">{{ $adm->length_of_stay }}d</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No admissions</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.patients.create') }}" class="btn btn-outline-primary w-100 btn-sm">
                            <i class="fas fa-user-plus me-1"></i>New Patient
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.appointments.create') }}" class="btn btn-outline-success w-100 btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>New Appt
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.billing.create') }}" class="btn btn-outline-warning w-100 btn-sm">
                            <i class="fas fa-file-invoice me-1"></i>New Invoice
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.admissions.create') }}" class="btn btn-outline-info w-100 btn-sm">
                            <i class="fas fa-bed me-1"></i>Admit Patient
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.doctors.create') }}" class="btn btn-outline-secondary w-100 btn-sm">
                            <i class="fas fa-user-md me-1"></i>Add Doctor
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-dark w-100 btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueData = @json($monthlyRevenue);
const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueData.map(r => monthNames[r.month - 1] + ' ' + r.year),
        datasets: [{
            label: 'Revenue ($)',
            data: revenueData.map(r => r.total),
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#0d6efd',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => '$' + v.toLocaleString() }
            }
        }
    }
});

// Appointments Doughnut
const apptCtx = document.getElementById('appointmentsChart').getContext('2d');
const apptData = @json($appointmentsByStatus);

new Chart(apptCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(apptData).map(k => k.replace('_', ' ').toUpperCase()),
        datasets: [{
            data: Object.values(apptData),
            backgroundColor: ['#0d6efd','#198754','#ffc107','#20c997','#dc3545','#6c757d'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } }
    }
});
</script>
@endpush
