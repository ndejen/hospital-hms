@extends('layouts.app')

@section('title', 'Register Patient')
@section('page-title', 'Register Patient')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item active">Register</li>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.patients.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-user me-2"></i>Personal Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="">Unknown</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><i class="fas fa-notes-medical me-2"></i>Medical Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Known Allergies</label>
                            <textarea name="known_allergies" class="form-control" rows="2" placeholder="e.g. Penicillin, Aspirin...">{{ old('known_allergies') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chronic Diseases</label>
                            <textarea name="chronic_diseases" class="form-control" rows="2" placeholder="e.g. Hypertension, Diabetes...">{{ old('chronic_diseases') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-phone-alt me-2"></i>Emergency Contact</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Relationship</label>
                        <select name="emergency_contact_relation" class="form-select">
                            <option value="">Select</option>
                            @foreach(['Spouse','Parent','Sibling','Child','Relative','Friend'] as $rel)
                            <option value="{{ $rel }}" {{ old('emergency_contact_relation') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><i class="fas fa-user-lock me-2"></i>Portal Access</div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="create_portal_account" id="portalAccess" value="1" {{ old('create_portal_account') ? 'checked' : '' }}>
                        <label class="form-check-label" for="portalAccess" style="font-size:13px">
                            Create patient portal account
                        </label>
                    </div>
                    <small class="text-muted" style="font-size:12px">Requires email. Default password: Password@123</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fas fa-save me-1"></i>Register Patient
                </button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
