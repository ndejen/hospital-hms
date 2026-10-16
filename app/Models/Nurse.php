<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id', 'nurse_number', 'specialization',
        'phone', 'license_number', 'joining_date', 'shift', 'is_active',
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

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function nursingNotes()
    {
        return $this->hasMany(NursingNote::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->name;
    }
}
