<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'texte',
        'image',
        'user_id',
        'spectacles',
        'url_video',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function spectacles()
    {
        return $this->belongsToMany(Spectacle::class);
    }

}
