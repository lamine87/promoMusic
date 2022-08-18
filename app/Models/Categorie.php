<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    // public function medias()
    // {
    //     return $this->belongsToMany(Media::class);
    // }
    public function medias()
    {
        // return $this->belongsToMany('App\Media')->withPivot('categorie_id', 'media_id');

        return $this->belongsToMany('App\Media')
        ->using('App\CategorieMedia')
        ->withPivot([
            'categorie_id',
            'media_id',
        ]);
    }


}
