<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name', 'last_name', 'email', 'password',
        'role', 'specialty', 'phone', 'active'
    ];

    protected $hidden = ['password', 'remember_token'];

    // Relaciones
    public function medicalHistories(): HasMany
    {
        return $this->hasMany(MedicalHistory::class, 'id_user', 'id_user');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(MedicalAppointment::class, 'id_user', 'id_user');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'id_user', 'id_user');
    }
}