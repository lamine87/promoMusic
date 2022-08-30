<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['url_video', 'texte','title','image','pays_id','categories'];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class)->withTimestamps();
    // }


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
