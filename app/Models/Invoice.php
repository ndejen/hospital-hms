<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'admission_id', 'invoice_number',
        'invoice_date', 'due_date', 'subtotal', 'tax', 'discount', 'total',
        'amount_paid', 'balance_due', 'status', 'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'discount'     => 'decimal:2',
        'total'        => 'decimal:2',
        'amount_paid'  => 'decimal:2',
        'balance_due'  => 'decimal:2',
    ];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function admission()   { return $this->belongsTo(Admission::class); }
    public function items()       { return $this->hasMany(InvoiceItem::class); }
    public function payments()    { return $this->hasMany(Payment::class); }
}
