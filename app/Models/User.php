<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role checks
    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isDoctor(): bool       { return $this->role === 'doctor'; }
    public function isNurse(): bool        { return $this->role === 'nurse'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isLaboratory(): bool   { return $this->role === 'laboratory'; }
    public function isPharmacist(): bool   { return $this->role === 'pharmacist'; }
    public function isPatient(): bool      { return $this->role === 'patient'; }

    // Relationships
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Dashboard route helper
    public function dashboardRoute(): string
    {
        return $this->role . '.dashboard';
    }

    // Accessor for full role label
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'        => 'Administrator',
            'doctor'       => 'Doctor',
            'nurse'        => 'Nurse',
            'receptionist' => 'Receptionist',
            'laboratory'   => 'Laboratory Staff',
            'pharmacist'   => 'Pharmacist',
            'patient'      => 'Patient',
            default        => ucfirst($this->role),
        };
    }
}
