<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasFactory;

    protected $table = 'lab_results';

    protected $fillable = [
        'lab_request_id', 'laboratory_test_id', 'patient_id',
        'result_value', 'unit', 'normal_range', 'interpretation',
        'notes', 'performed_by', 'performed_at', 'is_verified',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function labRequest()     { return $this->belongsTo(LabRequest::class); }
    public function test()           { return $this->belongsTo(LaboratoryTest::class, 'laboratory_test_id'); }
    public function patient()        { return $this->belongsTo(Patient::class); }
    public function performedBy()    { return $this->belongsTo(User::class, 'performed_by'); }
}
