<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'head_doctor', 'phone', 'location', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
