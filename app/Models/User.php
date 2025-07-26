<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'telefono',
        'dni',
        'obra_social',
        'ficha_medica',
        'google_id',
        'role',
        'current_team_id',
        'profile_photo_path',
    ];

    /**
     * Los atributos que deben ocultarse en arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Los atributos que deben castearse.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accesores personalizados.
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relaciones con otros modelos.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Verifica si el usuario es administrador.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
