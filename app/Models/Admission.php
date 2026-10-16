<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'ward_id', 'bed_id', 'admission_number',
        'admission_date', 'discharge_date', 'status', 'admission_reason',
        'discharge_summary', 'discharge_condition',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(Doctor::class); }
    public function ward()    { return $this->belongsTo(Ward::class); }
    public function bed()     { return $this->belongsTo(Bed::class); }
    public function nursingNotes() { return $this->hasMany(NursingNote::class); }

    public function getLengthOfStayAttribute(): ?int
    {
        if ($this->discharge_date) {
            return (int) $this->admission_date->diffInDays($this->discharge_date);
        }
        return (int) $this->admission_date->diffInDays(now());
    }
}
