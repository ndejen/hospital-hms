@extends('layouts.app')
@section('title', 'New Appointment')
@section('page-title', 'Schedule Appointment')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-plus me-2"></i>New Appointment</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.appointments.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient *</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->full_name }} ({{ $p->patient_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor *</label>
                            <select name="doctor_id" class="form-select" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->full_name }} — {{ $doc->specialization }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Time *</label>
                            <select name="appointment_time" class="form-select" required>
                                @foreach(['09:00','09:30','10:00','10:30','11:00','11:30','12:00','14:00','14:30','15:00','15:30','16:00','16:30'] as $t)
                                <option value="{{ $t }}" {{ old('appointment_time') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="new" {{ old('type') === 'new' ? 'selected' : '' }}>New Visit</option>
                                <option value="follow_up" {{ old('type') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                                <option value="emergency" {{ old('type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason for Visit</label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="Describe the reason for this appointment...">{{ old('reason') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Schedule Appointment</button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
