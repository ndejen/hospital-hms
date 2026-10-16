<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\LaboratoryController as AdminLabController;
use App\Http\Controllers\Admin\BillingController as AdminBillingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\NurseController as AdminNurseController;

use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\DiagnosisController;
use App\Http\Controllers\Doctor\LabRequestController;

use App\Http\Controllers\Nurse\DashboardController as NurseDashboard;
use App\Http\Controllers\Nurse\PatientController as NursePatientController;
use App\Http\Controllers\Nurse\VitalSignController;
use App\Http\Controllers\Nurse\AdmissionController as NurseAdmissionController;
use App\Http\Controllers\Nurse\NurseNoteController;

use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboard;
use App\Http\Controllers\Receptionist\PatientController as ReceptionistPatientController;
use App\Http\Controllers\Receptionist\AppointmentController as ReceptionistAppointmentController;
use App\Http\Controllers\Receptionist\BillingController as ReceptionistBillingController;

use App\Http\Controllers\Laboratory\DashboardController as LabDashboard;
use App\Http\Controllers\Laboratory\TestController;
use App\Http\Controllers\Laboratory\LabResultController;

use App\Http\Controllers\Pharmacist\DashboardController as PharmacistDashboard;
use App\Http\Controllers\Pharmacist\MedicineController as PharmacistMedicineController;
use App\Http\Controllers\Pharmacist\PrescriptionController as PharmacistPrescriptionController;
use App\Http\Controllers\Pharmacist\StockController;

use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\ProfileController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Doctors
    Route::resource('doctors', AdminDoctorController::class);

    // Nurses
    Route::resource('nurses', AdminNurseController::class);

    // Patients
    Route::resource('patients', AdminPatientController::class);

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Appointments
    Route::resource('appointments', AdminAppointmentController::class);
    Route::patch('appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Admissions
    Route::resource('admissions', AdminAdmissionController::class);

    // Medicines
    Route::resource('medicines', MedicineController::class);
    Route::get('medicines-categories', [MedicineController::class, 'categories'])->name('medicines.categories');

    // Laboratory
    Route::resource('laboratory/tests', AdminLabController::class)->names([
        'index' => 'lab.tests.index',
        'create' => 'lab.tests.create',
        'store' => 'lab.tests.store',
        'show' => 'lab.tests.show',
        'edit' => 'lab.tests.edit',
        'update' => 'lab.tests.update',
        'destroy' => 'lab.tests.destroy',
    ]);

    // Billing
    Route::resource('billing', AdminBillingController::class);
    Route::get('billing/{invoice}/print', [AdminBillingController::class, 'print'])->name('billing.print');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/patients', [ReportController::class, 'patients'])->name('reports.patients');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/laboratory', [ReportController::class, 'laboratory'])->name('reports.laboratory');
    Route::get('reports/pharmacy', [ReportController::class, 'pharmacy'])->name('reports.pharmacy');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Doctor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboard::class, 'index'])->name('dashboard');
    Route::resource('patients', DoctorPatientController::class)->only(['index', 'show']);
    Route::resource('appointments', DoctorAppointmentController::class);
    Route::patch('appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('diagnoses', DiagnosisController::class);
    Route::resource('lab-requests', LabRequestController::class);
    Route::get('lab-results', [LabRequestController::class, 'results'])->name('lab-results.index');
});

/*
|--------------------------------------------------------------------------
| Nurse Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
    Route::get('/dashboard', [NurseDashboard::class, 'index'])->name('dashboard');
    Route::resource('patients', NursePatientController::class)->only(['index', 'show']);
    Route::resource('vital-signs', VitalSignController::class);
    Route::resource('admissions', NurseAdmissionController::class);
    Route::resource('nurse-notes', NurseNoteController::class);
});

/*
|--------------------------------------------------------------------------
| Receptionist Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', [ReceptionistDashboard::class, 'index'])->name('dashboard');
    Route::resource('patients', ReceptionistPatientController::class);
    Route::resource('appointments', ReceptionistAppointmentController::class);
    Route::patch('appointments/{appointment}/checkin', [ReceptionistAppointmentController::class, 'checkIn'])->name('appointments.checkin');
    Route::patch('appointments/{appointment}/checkout', [ReceptionistAppointmentController::class, 'checkOut'])->name('appointments.checkout');
    Route::resource('billing', ReceptionistBillingController::class)->only(['index', 'show']);
});

/*
|--------------------------------------------------------------------------
| Laboratory Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:laboratory'])->prefix('laboratory')->name('laboratory.')->group(function () {
    Route::get('/dashboard', [LabDashboard::class, 'index'])->name('dashboard');
    Route::resource('tests', TestController::class);
    Route::resource('results', LabResultController::class);
    Route::get('results/{result}/print', [LabResultController::class, 'print'])->name('results.print');
});

/*
|--------------------------------------------------------------------------
| Pharmacist Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->name('pharmacist.')->group(function () {
    Route::get('/dashboard', [PharmacistDashboard::class, 'index'])->name('dashboard');
    Route::resource('medicines', PharmacistMedicineController::class);
    Route::resource('prescriptions', PharmacistPrescriptionController::class);
    Route::patch('prescriptions/{prescription}/dispense', [PharmacistPrescriptionController::class, 'dispense'])->name('prescriptions.dispense');
    Route::resource('stock', StockController::class);
});

/*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboard::class, 'index'])->name('dashboard');
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update']);
    Route::resource('appointments', PatientAppointmentController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('medical-history', MedicalHistoryController::class)->only(['index', 'show']);
});
