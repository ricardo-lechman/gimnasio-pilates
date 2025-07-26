<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cama extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(reserva::class);
    }
}
