<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'medicine_id', 'dosage', 'frequency',
        'duration_days', 'quantity', 'instructions', 'is_dispensed',
    ];

    protected $casts = [
        'is_dispensed' => 'boolean',
    ];

    public function prescription() { return $this->belongsTo(Prescription::class); }
    public function medicine()     { return $this->belongsTo(Medicine::class); }
}
