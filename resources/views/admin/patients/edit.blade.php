@extends('layouts.app')
@section('title', 'Edit Patient')
@section('page-title', 'Edit Patient')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.patients.show', $patient) }}">{{ $patient->full_name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<form method="POST" action="{{ route('admin.patients.update', $patient) }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-user-edit me-2"></i>Edit Patient Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $patient->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $patient->last_name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender *</label>
                            <select name="gender" class="form-select" required>
                                @foreach(['male','female','other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $patient->gender) === $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="">Unknown</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $patient->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $patient->address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $patient->city) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $patient->state) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $patient->zip_code) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Known Allergies</label>
                            <textarea name="known_allergies" class="form-control" rows="2">{{ old('known_allergies', $patient->known_allergies) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chronic Diseases</label>
                            <textarea name="chronic_diseases" class="form-control" rows="2">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Emergency Contact</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Relationship</label>
                        <select name="emergency_contact_relation" class="form-select">
                            <option value="">Select</option>
                            @foreach(['Spouse','Parent','Sibling','Child','Relative','Friend'] as $rel)
                            <option value="{{ $rel }}" {{ old('emergency_contact_relation', $patient->emergency_contact_relation) === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
