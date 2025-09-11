<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'Cama_id',
        'cronograma_id',
        'status',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Cama()
    {
        return $this->belongsTo(Cama::class);
    }

    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class);
    }

    public function pago()
    {
        return $this->hasOne(pago::class);
    }
}
