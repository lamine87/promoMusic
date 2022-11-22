<?php

namespace App\Http\Controllers\Front;



use App\Models\Pays;
use App\Models\Media;
use App\Models\Actualite;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class PageController extends Controller
{

    public function mediaByUser(Request $request)
    {
         $user = Auth::user();
        $medias = Media::where('user_id', '=',$request->id)
        ->orderBy('created_at', 'DESC')->get();
        foreach ($medias as $media)
        {
            $media['image'] = env('BASE_URL').$media['image'];
        }
        return $medias->toJson(JSON_PRETTY_PRINT);
    }



    // Get category
    public function voirCategorie(Request $request){
        $medias = Media::where('is_online','=',1)
            ->join('categorie_media', 'media.id', '=', 'categorie_media.media_id')
            ->join('categories', 'categories.id', '=', 'categorie_media.categorie_id')
            ->where('categorie_id', '=', $request->id)
            ->orderBy('created_at', 'DESC')->get();
            foreach ($medias as $media)
            {
                $media['image'] = env('BASE_URL').$media['image'];
            }
            return $medias->toJson(JSON_PRETTY_PRINT);
    }


// Filter media by categorie
public function mediaByContinent(Request $request){

    $medias = Media::where('is_online','=',1)
    ->where('pays_id', '=',$request->id)
    ->orderBy('created_at', 'DESC')->get();
     foreach ($medias as $media)
        {
            $media['image'] = env('BASE_URL').$media['image'];
        }
    return $medias->toJson(JSON_PRETTY_PRINT);
}

// Filter media for user
public function tag(Request $request){
    $medias = Media::where('is_online','=',1)
    ->where('user_id', '=',$request->id)
    ->orderBy('created_at', 'DESC')->get();
     foreach ($medias as $media)
        {
            $media['image'] = env('BASE_URL').$media['image'];
        }
    return $medias->toJson(JSON_PRETTY_PRINT);
}

    // Get all media continent
    public function continent(){

        $pays = Pays::all();
            return response()->json([JSON_PRETTY_PRINT,
            'pays' => $pays,
          ]);
   }

   // Get all media category
   public function categorie(){

    $categorie = Categorie::all();
        return response()->json([JSON_PRETTY_PRINT,
        'categorie' => $categorie,
      ]);
}

}
