@extends('layouts.app')

@section('title', $patient->full_name)
@section('page-title', 'Patient Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item active">{{ $patient->full_name }}</li>
@endsection

@section('content')
<div class="row g-3">
    {{-- Patient Info Card --}}
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="avatar avatar-lg mx-auto mb-3" style="background:linear-gradient(135deg,#0d6efd,#6f42c1)">
                {{ strtoupper(substr($patient->first_name, 0, 1)) }}
            </div>
            <h5 class="mb-0">{{ $patient->full_name }}</h5>
            <div class="text-muted mb-2" style="font-size:13px">{{ $patient->patient_number }}</div>
            @if($patient->blood_group)
            <span class="badge bg-danger-subtle text-danger border border-danger mb-3">{{ $patient->blood_group }}</span>
            @endif
            <div class="text-start">
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px">
                    <span class="text-muted">Age</span><span>{{ $patient->age }} years</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px">
                    <span class="text-muted">Gender</span><span>{{ ucfirst($patient->gender) }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px">
                    <span class="text-muted">DOB</span><span>{{ $patient->date_of_birth->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px">
                    <span class="text-muted">Phone</span><span>{{ $patient->phone ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px">
                    <span class="text-muted">Email</span><span style="font-size:12px">{{ $patient->email ?? '—' }}</span>
                </div>
                @if($patient->known_allergies)
                <div class="mt-2">
                    <div class="text-muted" style="font-size:12px">Allergies</div>
                    <div class="text-danger" style="font-size:13px">{{ $patient->known_allergies }}</div>
                </div>
                @endif
                @if($patient->chronic_diseases)
                <div class="mt-2">
                    <div class="text-muted" style="font-size:12px">Chronic Diseases</div>
                    <div style="font-size:13px">{{ $patient->chronic_diseases }}</div>
                </div>
                @endif
            </div>
            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-outline-primary btn-sm flex-fill">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
        </div>

        {{-- Emergency Contact --}}
        @if($patient->emergency_contact_name)
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-phone-alt me-2"></i>Emergency Contact</div>
            <div class="card-body" style="font-size:13px">
                <div class="fw-500">{{ $patient->emergency_contact_name }}</div>
                <div class="text-muted">{{ $patient->emergency_contact_relation }}</div>
                <div>{{ $patient->emergency_contact_phone }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="patientTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#appointments">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#records">Medical Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#prescriptions">Prescriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#billing">Billing</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    {{-- Appointments --}}
                    <div class="tab-pane fade show active" id="appointments">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Doctor</th><th>Reason</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($patient->appointments->take(10) as $appt)
                                <tr>
                                    <td>{{ $appt->appointment_date->format('d M Y') }}</td>
                                    <td>{{ $appt->doctor->full_name ?? 'N/A' }}</td>
                                    <td style="font-size:12px">{{ Str::limit($appt->reason, 30) }}</td>
                                    <td>{!! $appt->status_badge !!}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No appointments</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Medical Records --}}
                    <div class="tab-pane fade" id="records">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Doctor</th><th>Diagnosis</th></tr></thead>
                            <tbody>
                                @forelse($patient->medicalRecords->take(10) as $rec)
                                <tr>
                                    <td>{{ $rec->visit_date->format('d M Y') }}</td>
                                    <td>{{ $rec->doctor->full_name ?? 'N/A' }}</td>
                                    <td style="font-size:12px">{{ Str::limit($rec->diagnosis, 40) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">No records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Prescriptions --}}
                    <div class="tab-pane fade" id="prescriptions">
                        @forelse($patient->prescriptions->take(5) as $prx)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-500" style="font-size:13px">{{ $prx->prescription_number }}</div>
                                    <div class="text-muted" style="font-size:12px">{{ $prx->prescribed_date->format('d M Y') }}</div>
                                </div>
                                <span class="badge {{ $prx->status === 'dispensed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($prx->status) }}</span>
                            </div>
                            @foreach($prx->items as $item)
                            <div style="font-size:12px;color:#495057;margin-top:4px">
                                • {{ $item->medicine->name ?? 'N/A' }} — {{ $item->dosage }} {{ $item->frequency }}
                            </div>
                            @endforeach
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">No prescriptions</div>
                        @endforelse
                    </div>

                    {{-- Billing --}}
                    <div class="tab-pane fade" id="billing">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Invoice#</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($patient->invoices->take(10) as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_number }}</td>
                                    <td>{{ $inv->invoice_date->format('d M Y') }}</td>
                                    <td>${{ number_format($inv->total, 2) }}</td>
                                    <td>
                                        <span class="badge {{ match($inv->status) { 'paid' => 'bg-success', 'partial' => 'bg-warning', 'overdue' => 'bg-danger', default => 'bg-secondary' } }}">
                                            {{ ucfirst($inv->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No invoices</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
