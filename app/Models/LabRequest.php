<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabRequest extends Model
{
    use HasFactory;

    protected $table = 'lab_requests';

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'request_number',
        'requested_date', 'priority', 'status', 'clinical_notes',
    ];

    protected $casts = [
        'requested_date' => 'date',
    ];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(Doctor::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }

    public function tests()
    {
        return $this->belongsToMany(LaboratoryTest::class, 'lab_request_tests', 'lab_request_id', 'laboratory_test_id');
    }

    public function results()
    {
        return $this->hasMany(LabResult::class);
    }
}
