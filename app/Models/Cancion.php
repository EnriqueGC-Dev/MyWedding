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
    'deezer_id',
    'likes',
    'dislikes',
    'user_like',
    'user_dislike',
    ];

    protected $casts = [
        'user_like' => 'array',
        'user_dislike' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
