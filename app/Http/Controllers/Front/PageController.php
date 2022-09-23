<?php

namespace App\Http\Controllers\Front;



use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Actualite;
use App\Models\Categorie;
use App\Models\Pays;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    //
    public function voirCategorie(Request $request){
       $medias = DB::table('media')
            ->join('categorie_media', 'media.id', '=', 'categorie_media.media_id')
            ->join('categories', 'categories.id', '=', 'categorie_media.categorie_id')
            ->where('is_online','=',1)
            ->where('categorie_id', '=', $request->id)
            ->orderBy('created_at', 'DESC')->get();

            return $medias->toJson(JSON_PRETTY_PRINT);
    }



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


    public function continent(){

        $pays = Pays::all();
            return response()->json([JSON_PRETTY_PRINT,
            'pays' => $pays,
          ]);
   }

   public function categorie(){

    $categorie = Categorie::all();
        return response()->json([JSON_PRETTY_PRINT,
        'categorie' => $categorie,
      ]);
}

}
