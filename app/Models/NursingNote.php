<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NursingNote extends Model
{
    use HasFactory;

    protected $table = 'nursing_notes';

    protected $fillable = ['patient_id', 'nurse_id', 'admission_id', 'note', 'noted_at'];

    protected $casts = [
        'noted_at' => 'datetime',
    ];

    public function patient()   { return $this->belongsTo(Patient::class); }
    public function nurse()     { return $this->belongsTo(Nurse::class); }
    public function admission() { return $this->belongsTo(Admission::class); }
}
