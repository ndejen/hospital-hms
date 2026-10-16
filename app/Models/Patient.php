<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'patient_number', 'first_name', 'last_name', 'gender',
        'date_of_birth', 'phone', 'email', 'address', 'city', 'state', 'zip_code',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        'blood_group', 'occupation', 'known_allergies', 'chronic_diseases', 'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labRequests()
    {
        return $this->hasMany(LabRequest::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function getLatestVitalSignsAttribute()
    {
        return $this->vitalSigns()->latest('recorded_at')->first();
    }

    public function getCurrentAdmissionAttribute()
    {
        return $this->admissions()->where('status', 'admitted')->latest()->first();
    }
}
