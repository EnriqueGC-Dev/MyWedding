<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $table = 'canciones';

    protected $fillable = [
        'title',
        'artist',
        'photo',
        'url',
        'preview',
        'likes',
        'dislikes',
    ];
}
