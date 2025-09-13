<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cama extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'estado',
    ];

    /**
     * Relación: una cama puede tener muchas reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}

