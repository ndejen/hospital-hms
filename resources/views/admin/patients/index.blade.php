@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')

@section('breadcrumb')
    <li class="breadcrumb-item active">Patients</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-procedures me-2"></i>Patient List ({{ $patients->total() }})</span>
        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i>Register Patient
        </a>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search by name, number, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select form-select-sm">
                    <option value="">All Genders</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="blood_group" class="form-select form-select-sm">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Age / Gender</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="background:{{ $patient->gender === 'male' ? 'linear-gradient(135deg,#0d6efd,#6f42c1)' : 'linear-gradient(135deg,#d63384,#fd7e14)' }}">
                                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:13px">{{ $patient->full_name }}</div>
                                    <div style="font-size:11px;color:#6c757d">{{ $patient->patient_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px">{{ $patient->age }} yrs</div>
                            <div style="font-size:11px;color:#6c757d">{{ ucfirst($patient->gender) }}</div>
                        </td>
                        <td>
                            @if($patient->blood_group)
                            <span class="badge bg-danger-subtle text-danger border border-danger">{{ $patient->blood_group }}</span>
                            @else <span class="text-muted">—</span> @endif
                        </td>
                        <td style="font-size:13px">{{ $patient->phone ?? '—' }}</td>
                        <td style="font-size:12px;color:#6c757d">{{ $patient->created_at->format('d M Y') }}</td>
                        <td>
                            @if($patient->is_active)
                            <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-icon btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-icon btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-icon btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No patients found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($patients->hasPages())
    <div class="card-body border-top">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
