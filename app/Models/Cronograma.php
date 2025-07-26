<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cronograma extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(reserva::class);
    }
}
