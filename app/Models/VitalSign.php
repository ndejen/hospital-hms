<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'nurse_id', 'appointment_id', 'temperature', 'blood_pressure',
        'pulse_rate', 'respiratory_rate', 'oxygen_saturation', 'weight', 'height',
        'bmi', 'notes', 'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function nurse()       { return $this->belongsTo(Nurse::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}
