<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'record_number', 'visit_date',
        'chief_complaint', 'history_of_illness', 'physical_examination',
        'diagnosis', 'treatment_plan', 'follow_up_instructions',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(Doctor::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function diagnoses() { return $this->hasMany(Diagnosis::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
}
