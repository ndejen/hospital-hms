<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 'name', 'ward_number', 'type', 'total_beds', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department() { return $this->belongsTo(Department::class); }
    public function beds()       { return $this->hasMany(Bed::class); }
    public function admissions() { return $this->hasMany(Admission::class); }

    public function getAvailableBedsCountAttribute(): int
    {
        return $this->beds()->where('status', 'available')->count();
    }
}
