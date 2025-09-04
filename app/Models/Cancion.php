<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $table = 'canciones';

    protected $fillable = [
        'user_id',
        'title',
        'artist',
        'photo',
        'url',
        'preview',
        'likes',
        'dislikes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
