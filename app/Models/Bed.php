<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = ['ward_id', 'bed_number', 'status', 'notes'];

    public function ward()      { return $this->belongsTo(Ward::class); }
    public function admission() { return $this->hasOne(Admission::class); }
}
