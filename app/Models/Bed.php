<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relaciones
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
