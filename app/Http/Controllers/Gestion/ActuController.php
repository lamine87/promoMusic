<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Categorie;
use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;


class ActuController extends Controller
{
    //
    public function actu(){
        //$actualite = DB::table('actualites')
         //   ->orderBy('created_at', 'desc')->paginate(6);


         $actualites = Actualite::where('is_online','=',1)->get();
         foreach ($actualites as $actualite)
         {
             $actualite['image'] = env('BASE_URL').$actualite['image'];
         }

         return response()->json([JSON_PRETTY_PRINT,
         'actualites' => $actualites,
      ]);
     }

     public function store(Request $request)
    {
        //
        $request->validate(
            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'image' =>  'required|image|max:1999',
            ]
        );
        if ($request->hasFile('image')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $fileName = $request->file('image')->getClientOriginalName();

            //Telechargement de l'image
            $request->file('image')->storeAs('public/upload', $uniqid.$fileName);

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            $img->save('storage/image/'.$uniqid.$fileName);
        }

        $actualite = new Actualite();
        $actualite->url_video = $request->url_video;
        $actualite->texte = $request->texte;
        $actualite->title = $request->title;
        $actualite->image = $uniqid.$fileName;
        $actualite->save();
        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'actualites' => $actualite,
         ]);
    }

    public function show($id)
    {
        //
        $actualite = Actualite::find($id);

        return $actualite->toJson(JSON_PRETTY_PRINT);
    }

    public function update(Request $request, $id )
    {
        $actualite = Actualite::find($id);
        $request->validate(

            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'image' =>  'required|image|max:1999',
            ]

        );

        if ($request->hasFile('image')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $fileName = $request->file('image')->getClientOriginalName();

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            $img->save(public_path('storage/image/'.$uniqid.$fileName));
        }

        $actualite->url_video = $request->url_video;
        $actualite->texte = $request->texte;
        $actualite->title = $request->title;
        $actualite->image = $uniqid.$fileName;
        $actualite->save();


        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'actualites' => $actualite,
         ]);
    }

    public function destroy($id)
    {
        return Categorie::destroy($id);
    }
}
