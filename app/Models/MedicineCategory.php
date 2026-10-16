<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    use HasFactory;

    protected $table = 'medicine_categories';

    protected $fillable = ['name', 'description'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'category_id');
    }
}
