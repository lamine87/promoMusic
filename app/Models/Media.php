<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['texte','title','image','pays_id','user_id','categories','url_video'];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function categories()
    {
        return $this->belongsToMany(Categorie::class);
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->latest()
            ->whereNull('parent_id');
    }

}
