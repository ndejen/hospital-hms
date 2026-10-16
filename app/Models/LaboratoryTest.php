<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryTest extends Model
{
    use HasFactory;

    protected $table = 'laboratory_tests';

    protected $fillable = [
        'test_code', 'name', 'category', 'description', 'price',
        'normal_range', 'unit', 'turnaround_hours', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function labRequests()
    {
        return $this->belongsToMany(LabRequest::class, 'lab_request_tests');
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }
}
