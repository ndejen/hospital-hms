<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'department_id', 'appointment_number',
        'appointment_date', 'appointment_time', 'status', 'reason', 'notes', 'type',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function labRequest()
    {
        return $this->hasOne(LabRequest::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'scheduled'   => '<span class="badge bg-primary">Scheduled</span>',
            'confirmed'   => '<span class="badge bg-info">Confirmed</span>',
            'checked_in'  => '<span class="badge bg-warning">Checked In</span>',
            'completed'   => '<span class="badge bg-success">Completed</span>',
            'cancelled'   => '<span class="badge bg-danger">Cancelled</span>',
            'no_show'     => '<span class="badge bg-secondary">No Show</span>',
            default       => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
