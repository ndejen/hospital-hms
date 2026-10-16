@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-calendar-check me-2"></i>Appointments ({{ $appointments->total() }})</span>
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>New Appointment
        </a>
    </div>
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search patient/number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['scheduled','confirmed','checked_in','completed','cancelled','no_show'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="doctor_id" class="form-select form-select-sm">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>#</th><th>Patient</th><th>Doctor</th><th>Date & Time</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($appointments as $appt)
                    <tr>
                        <td style="font-size:12px;color:#6c757d">{{ $appt->appointment_number }}</td>
                        <td>
                            <div class="fw-500" style="font-size:13px">{{ $appt->patient->full_name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#6c757d">{{ $appt->patient->patient_number ?? '' }}</div>
                        </td>
                        <td style="font-size:13px">{{ $appt->doctor->full_name ?? 'N/A' }}</td>
                        <td>
                            <div style="font-size:13px">{{ $appt->appointment_date->format('d M Y') }}</div>
                            <div style="font-size:11px;color:#6c757d">{{ $appt->appointment_time }}</div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($appt->type) }}</span></td>
                        <td>{!! $appt->status_badge !!}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.appointments.show', $appt) }}" class="btn btn-icon btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.appointments.edit', $appt) }}" class="btn btn-icon btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-icon btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No appointments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($appointments->hasPages())
    <div class="card-body border-top">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
