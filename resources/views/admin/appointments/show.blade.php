@extends('layouts.app')
@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-calendar-check me-2"></i>{{ $appointment->appointment_number }}</span>
                {!! $appointment->status_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:12px">PATIENT</label>
                        <div class="fw-500">{{ $appointment->patient->full_name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $appointment->patient->patient_number ?? '' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:12px">DOCTOR</label>
                        <div class="fw-500">{{ $appointment->doctor->full_name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $appointment->doctor->specialization ?? '' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted" style="font-size:12px">DATE</label>
                        <div>{{ $appointment->appointment_date->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted" style="font-size:12px">TIME</label>
                        <div>{{ $appointment->appointment_time }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted" style="font-size:12px">TYPE</label>
                        <div>{{ ucfirst(str_replace('_',' ',$appointment->type)) }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted" style="font-size:12px">DEPARTMENT</label>
                        <div>{{ $appointment->department->name ?? 'N/A' }}</div>
                    </div>
                    @if($appointment->reason)
                    <div class="col-12">
                        <label class="text-muted" style="font-size:12px">REASON</label>
                        <div>{{ $appointment->reason }}</div>
                    </div>
                    @endif
                    @if($appointment->notes)
                    <div class="col-12">
                        <label class="text-muted" style="font-size:12px">NOTES</label>
                        <div>{{ $appointment->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
                <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                        @foreach(['scheduled','confirmed','checked_in','completed','cancelled','no_show'] as $s)
                        <option value="{{ $s }}" {{ $appointment->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Patient Info</div>
            <div class="card-body" style="font-size:13px">
                <div class="mb-1"><span class="text-muted">Age:</span> {{ $appointment->patient->age ?? 'N/A' }} years</div>
                <div class="mb-1"><span class="text-muted">Blood Group:</span> {{ $appointment->patient->blood_group ?? '—' }}</div>
                <div class="mb-1"><span class="text-muted">Phone:</span> {{ $appointment->patient->phone ?? '—' }}</div>
                @if($appointment->patient->known_allergies)
                <div class="mt-2 text-danger" style="font-size:12px"><i class="fas fa-exclamation-triangle me-1"></i>Allergies: {{ $appointment->patient->known_allergies }}</div>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.patients.show', $appointment->patient) }}" class="btn btn-outline-primary w-100 mt-2 btn-sm">
            <i class="fas fa-procedures me-1"></i>View Full Patient Profile
        </a>
    </div>
</div>
@endsection
