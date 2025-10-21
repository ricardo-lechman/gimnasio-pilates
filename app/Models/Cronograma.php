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

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    // 🔍 Método auxiliar opcional para formatear turno
    public function getTurnoAttribute()
    {
        return "{$this->date->format('d/m/Y')} ({$this->start_time} - {$this->end_time})";
    }
}

