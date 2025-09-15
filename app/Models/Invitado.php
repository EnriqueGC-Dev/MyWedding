<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'invitados_adicionales',
        'restricciones',
        'bebida_cena',
        'bebida_fiesta',
    ];

    protected $casts = [
        'invitados_adicionales' => 'array',
    ];
}