@php $role = auth()->user()->role; @endphp

{{-- ===================== ADMIN ===================== --}}
@if($role === 'admin')
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <div class="sidebar-section">Manage</div>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users-cog"></i> Users
    </a>
    <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
        <i class="fas fa-user-md"></i> Doctors
    </a>
    <a href="{{ route('admin.nurses.index') }}" class="{{ request()->routeIs('admin.nurses.*') ? 'active' : '' }}">
        <i class="fas fa-user-nurse"></i> Nurses
    </a>
    <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
        <i class="fas fa-procedures"></i> Patients
    </a>
    <a href="{{ route('admin.departments.index') }}" class="{{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
        <i class="fas fa-building"></i> Departments
    </a>

    <div class="sidebar-section">Operations</div>
    <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Appointments
    </a>
    <a href="{{ route('admin.admissions.index') }}" class="{{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}">
        <i class="fas fa-bed"></i> Admissions
    </a>
    <a href="{{ route('admin.lab.tests.index') }}" class="{{ request()->routeIs('admin.lab.*') ? 'active' : '' }}">
        <i class="fas fa-flask"></i> Laboratory
    </a>
    <a href="{{ route('admin.medicines.index') }}" class="{{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
        <i class="fas fa-pills"></i> Medicines
    </a>
    <a href="{{ route('admin.billing.index') }}" class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i> Billing
    </a>

    <div class="sidebar-section">System</div>
    <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Reports
    </a>
    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Settings
    </a>

{{-- ===================== DOCTOR ===================== --}}
@elseif($role === 'doctor')
    <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <div class="sidebar-section">Clinical</div>
    <a href="{{ route('doctor.appointments.index') }}" class="{{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Appointments
    </a>
    <a href="{{ route('doctor.patients.index') }}" class="{{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
        <i class="fas fa-procedures"></i> My Patients
    </a>
    <a href="{{ route('doctor.diagnoses.index') }}" class="{{ request()->routeIs('doctor.diagnoses.*') ? 'active' : '' }}">
        <i class="fas fa-stethoscope"></i> Diagnoses
    </a>
    <a href="{{ route('doctor.prescriptions.index') }}" class="{{ request()->routeIs('doctor.prescriptions.*') ? 'active' : '' }}">
        <i class="fas fa-prescription-bottle-alt"></i> Prescriptions
    </a>
    <a href="{{ route('doctor.lab-requests.index') }}" class="{{ request()->routeIs('doctor.lab-requests.*') ? 'active' : '' }}">
        <i class="fas fa-flask"></i> Lab Requests
    </a>
    <a href="{{ route('doctor.lab-results.index') }}" class="{{ request()->routeIs('doctor.lab-results.*') ? 'active' : '' }}">
        <i class="fas fa-vials"></i> Lab Results
    </a>

{{-- ===================== NURSE ===================== --}}
@elseif($role === 'nurse')
    <a href="{{ route('nurse.dashboard') }}" class="{{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <div class="sidebar-section">Care</div>
    <a href="{{ route('nurse.patients.index') }}" class="{{ request()->routeIs('nurse.patients.*') ? 'active' : '' }}">
        <i class="fas fa-procedures"></i> Patients
    </a>
    <a href="{{ route('nurse.vital-signs.index') }}" class="{{ request()->routeIs('nurse.vital-signs.*') ? 'active' : '' }}">
        <i class="fas fa-heartbeat"></i> Vital Signs
    </a>
    <a href="{{ route('nurse.admissions.index') }}" class="{{ request()->routeIs('nurse.admissions.*') ? 'active' : '' }}">
        <i class="fas fa-bed"></i> Admissions
    </a>
    <a href="{{ route('nurse.nurse-notes.index') }}" class="{{ request()->routeIs('nurse.nurse-notes.*') ? 'active' : '' }}">
        <i class="fas fa-notes-medical"></i> Nursing Notes
    </a>

{{-- ===================== RECEPTIONIST ===================== --}}
@elseif($role === 'receptionist')
    <a href="{{ route('receptionist.dashboard') }}" class="{{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <div class="sidebar-section">Front Desk</div>
    <a href="{{ route('receptionist.patients.index') }}" class="{{ request()->routeIs('receptionist.patients.*') ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i> Register Patient
    </a>
    <a href="{{ route('receptionist.appointments.index') }}" class="{{ request()->routeIs('receptionist.appointments.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Appointments
    </a>
    <a href="{{ route('receptionist.billing.index') }}" class="{{ request()->routeIs('receptionist.billing.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i> Billing
    </a>

{{-- ===================== LABORATORY ===================== --}}
@elseif($role === 'laboratory')
    <a href="{{ route('laboratory.dashboard') }}" class="{{ request()->routeIs('laboratory.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <div class="sidebar-section">Lab Work</div>
    <a href="{{ route('laboratory.tests.index') }}" class="{{ request()->routeIs('laboratory.tests.*') ? 'active' : '' }}">
        <i class="fas fa-flask"></i> Test Requests
    </a>
    <a href="{{ route('laboratory.results.index') }}" class="{{ request()->routeIs('laboratory.results.*') ? 'active' : '' }}">
        <i class="fas fa-vials"></i> Record Results
    </a>

{{-- ===================== PHARMACIST ===================== --}}
@elseif($role === 'pharmacist')
    <a href="{{ route('pharmacist.dashboard') }}" class="{{ request()->routeIs('pharmacist.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <div class="sidebar-section">Pharmacy</div>
    <a href="{{ route('pharmacist.prescriptions.index') }}" class="{{ request()->routeIs('pharmacist.prescriptions.*') ? 'active' : '' }}">
        <i class="fas fa-prescription"></i> Prescriptions
    </a>
    <a href="{{ route('pharmacist.medicines.index') }}" class="{{ request()->routeIs('pharmacist.medicines.*') ? 'active' : '' }}">
        <i class="fas fa-pills"></i> Medicines
    </a>
    <a href="{{ route('pharmacist.stock.index') }}" class="{{ request()->routeIs('pharmacist.stock.*') ? 'active' : '' }}">
        <i class="fas fa-boxes"></i> Stock Management
    </a>

{{-- ===================== PATIENT ===================== --}}
@elseif($role === 'patient')
    <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> My Dashboard
    </a>
    <div class="sidebar-section">My Health</div>
    <a href="{{ route('patient.profile.index') }}" class="{{ request()->routeIs('patient.profile.*') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i> My Profile
    </a>
    <a href="{{ route('patient.appointments.index') }}" class="{{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Appointments
    </a>
    <a href="{{ route('patient.medical-history.index') }}" class="{{ request()->routeIs('patient.medical-history.*') ? 'active' : '' }}">
        <i class="fas fa-history"></i> Medical History
    </a>
@endif

{{-- Common bottom items --}}
<div class="sidebar-section">Account</div>
<a href="{{ route('password.change') }}">
    <i class="fas fa-key"></i> Change Password
</a>
<form method="POST" action="{{ route('logout') }}" style="margin:0">
    @csrf
    <button type="submit" class="w-100 text-start" style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:10px;padding:10px 20px;color:rgba(255,255,255,0.55);font-size:13.5px">
        <i class="fas fa-sign-out-alt" style="width:20px;text-align:center"></i> Logout
    </button>
</form>
