<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id', 'patient_id', 'doctor_id', 'icd_code',
        'diagnosis_name', 'description', 'severity', 'diagnosed_date', 'is_chronic',
    ];

    protected $casts = [
        'diagnosed_date' => 'date',
        'is_chronic' => 'boolean',
    ];

    public function medicalRecord() { return $this->belongsTo(MedicalRecord::class); }
    public function patient()       { return $this->belongsTo(Patient::class); }
    public function doctor()        { return $this->belongsTo(Doctor::class); }
}
