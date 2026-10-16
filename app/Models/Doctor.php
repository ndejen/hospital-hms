<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id', 'doctor_number', 'specialization',
        'phone', 'license_number', 'joining_date', 'bio', 'availability', 'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labRequests()
    {
        return $this->hasMany(LabRequest::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function getFullNameAttribute(): string
    {
        return 'Dr. ' . $this->user->name;
    }

    public function getTodayAppointmentsCountAttribute(): int
    {
        return $this->appointments()
            ->whereDate('appointment_date', today())
            ->count();
    }
}
