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
        'date' => 'date',     // Mantener como date
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function getTurnoAttribute()
    {
        return "{$this->date->format('d/m/Y')} ({$this->start_time} - {$this->end_time})";
    }
}


