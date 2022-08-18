<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
     'texte','url_video', 'image', 'is_online', 'categorie_id', 'media_id'
    ];

    protected $table = 'media';


    protected $guarded = [];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function categories()
    // {
    //     return $this->belongsToMany(Categorie::class);
    // }

    public function categories()
    {
        return $this->belongsToMany('App\Categorie','categorie_media', 'categorie_id', 'media_id');
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->latest()
            ->whereNull('parent_id');
    }

}
