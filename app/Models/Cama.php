<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cama extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'estado'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}

