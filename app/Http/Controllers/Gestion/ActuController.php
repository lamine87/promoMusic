<?php

namespace App\Http\Controllers\Gestion;

use App\Models\User;
use App\Models\Media;
use App\Models\Actualite;
use App\Models\Spectacle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Madcoda\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\ImageManagerStatic as Image;

class ActuController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function actu()
    {
        $actualites = Actualite::where('is_online','=',1)->orderBy('created_at', 'DESC')->get();

        foreach ($actualites as $actualite)
        {
            $actualite['image'] = env('BASE_UR_ACTU').$actualite['image'];
        }
        return $actualites->toJson(JSON_PRETTY_PRINT);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate(
            [
                'title' => 'required | string | max:100',
                'texte' => 'required | string | max:250',
                'spectacles'=> 'required',
                'url_video' => 'required | string',
                'image' =>  'required | image',
            ]
        );

        if ($request->hasFile('image')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $fileName = $request->file('image')->getClientOriginalName();

            // Renommer le nom de l'image
            $rename = str_replace('','_',$uniqid).'-'.date('d-m-Y-H-i-').$fileName;

            //Telechargement de l'image
            // $request->file('image')->storeAs('public/uploadActu', $rename);

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            $img->save('storage/uploadActu/'.$rename);
        }
        // var_dump($user);
        Actualite::create([
        'title' => $request->title,
        'texte' => $request->texte,
        'image' => $rename,
        'url_video' => $request->url_video,
        'user_id' => auth()->user()->id,

        ])->spectacles()->attach($request->spectacles);


        return response()->json([JSON_PRETTY_PRINT,
            'message'=>'successful!',
            'status'=>true,
            'actualite' => [
                'url_video' => $request->url_video,
                'texte' => $request->texte,
                'title' => $request->title,
                'image' => $rename,
                'spectacles'=>$request->spectacles,
                'user'=>$user->id,
            ],
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $actualite = Actualite::find($id);
        return $actualite->toJson(JSON_PRETTY_PRINT);
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $actualite = Actualite::find($request->id);
        $request->validate(
            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'image' =>  'required | image',
                'spectacles'=> 'required',
            ]
        );

            $actualite->url_video = $request->url_video;
            $actualite->texte = $request->texte;
            $actualite->title = $request->title;
            $actualite->spectacles()->sync($request->spectacles);

        if ($request->hasFile('image')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $file = $request->file('image');

            $originalName = $file->getClientOriginalName();
            // Renommer le nom de l'image
            $fileName = str_replace('','_',$uniqid).'-'.date('d-m-Y-H-i-').$originalName;

            //Telechargement de l'image
            // $request->file('image')->storeAs('public/upload', $rename);

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            // Enregistrer image dans le repertoire
            $file->move('storage/uploadActu/', $fileName);

            // Supprimer l'ancienne image du repertoire
            FileFacade::delete(public_path('storage/uploadActu/' . $actualite->image));

            $actualite->image = $fileName;

            $img->save('storage/uploadActu/'.$fileName);
        }
        $actualite->save();
        return response()->json([JSON_PRETTY_PRINT,
            'message'=>'successful!',
            'status'=>true,
            'actualite' => $actualite,
            // 'users'=>$user,
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actualite = Actualite::find($id);
        $file_name = $actualite->image;
        $file_path = public_path('storage/uploadActu/'.$file_name);
        unlink($file_path);
        $actualite->delete();

        return response()->json([JSON_PRETTY_PRINT,
        'status'=>true,
        'message'=>'supprimer avec succès!',
        'actualite' => $actualite
         ]);

    }

    public function getSpectacles()
    {
        $spectacles = Spectacle::all();
        return $spectacles->toJson(JSON_PRETTY_PRINT);
    }


}
