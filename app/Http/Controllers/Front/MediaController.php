<?php

namespace App\Http\Controllers\Front;
use App\Models\Pays;
use App\Models\Media;
// use Google\Service\YouTube;
// use Dawson\Youtube\Facades\Youtube;
use App\Models\Categorie;
use Illuminate\Http\Request;
// use Google\Service\YouTube as ServiceYouTube;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Madcoda\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medias = Media::where('is_online','=',1)->orderBy('created_at', 'DESC')->get();
       // $medias = Media::where('is_online','=',1)->orderBy('created_at', 'DESC');

        foreach ($medias as $media)
        {
            $media['image'] = env('BASE_URL').$media['image'];
        }
        // $media = Media::all();
        //->orderBy('created_at', 'desc')->simplePaginate(20);
        return $medias->toJson(JSON_PRETTY_PRINT);
    }
    public function youtubeur()
    {
            // $video = Youtube::getVideoInfo('videos');
            // $m = Youtube::getChannelByName('YoMusic');
            // $channel = Youtube::getChannelById('YoMusic');
            // $videoId = Youtube::parseVIdFromURL('videos');
            // $playlists = Youtube::getPlaylistsByChannelId('UCsHds9fFTG0tz4p9LprUiGA');
            $activities = Youtube::getActivitiesByChannelId('UCsHds9fFTG0tz4p9LprUiGA');

        //    dd($activities);

        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'activities'=> $activities,


    ]);

    }

    public function videoInsert(Request $request)
    {
        $video = Youtube::upload($request->file('video')->getPathName(),[

            'title'=> $request->title,
            'description' => $request->description,

        ])->withThumbnail($request->file('image')->getPathName());
        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'video' => $video,

         ]);

    //  return "Video uploaded successfully. Video ID is ".$video->getVideoId();
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // if (auth()->guest()) {
        //     Session::flash('Vous devez être connecter');
        // }

        $user = Auth::user();
        // $user = User::find($request->id);
        // var_dump($user);
        $request->validate(
            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'image' =>  'required|image',
                'pays'  => 'required',
                'categories'=> 'required',
            ]
        );

        if ($request->hasFile('image')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $fileName = $request->file('image')->getClientOriginalName();

            // Renommer le nom de l'image
            $rename = str_replace('','_',$uniqid).'-'.date('d-m-Y-H-i-').$fileName;

            //Telechargement de l'image
            // $request->file('image')->storeAs('public/upload', $rename);

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            $img->save('storage/image/'.$rename);
        }
        // var_dump($user);

        Media::create([
        'url_video' => $request->url_video,
        'texte' => $request->texte,
        'title' => $request->title,
        'image' => $rename,
        'pays_id' => $request->pays,
        'user_id' => auth()->id(),
        //  $request->user()->id
        ])->categories()->attach($request->categories);


        return response()->json([JSON_PRETTY_PRINT,
            'message'=>'successful!',
            'status'=>true,
            'media' => [
                'url_video' => $request->url_video,
                'texte' => $request->texte,
                'title' => $request->title,
                'image' => $rename,
                'pays_id' => $request->pays,
                'categories'=>$request->categories,
                'user'=>$user,
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
        $media = Media::find($id);
        return $media->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id )
    {
        $user = Auth::user();
        $media = Media::find($request->id);
        $request->validate(
            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'image' =>  'file',
                'pays'  => 'required',
                'categories'=> 'required',
            ]
        );

            $media->url_video = $request->url_video;
            $media->texte = $request->texte;
            $media->title = $request->title;
            $media->pays_id = $request->pays;
            $media->categories()->sync($request->categories);

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
            $file->move('storage/image/', $fileName);

            // Supprimer l'ancienne image du repertoire
            FileFacade::delete(public_path('storage/image/' . $media->image));

            $media->image = $fileName;

            $img->save('storage/image/'.$fileName);
        }
        $media->save();
        return response()->json([JSON_PRETTY_PRINT,
            'message'=>'successful!',
            'status'=>true,
            'media' => $media,
            'users'=>$user,
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
        $media = Media::find($id);
        $file_name = $media->image;
        $file_path = public_path('storage/image/'.$file_name);
        unlink($file_path);
        $media->delete();

        return response()->json([JSON_PRETTY_PRINT,
        'status'=>true,
        'message'=>'supprimer avec succès!',
        'media' => $media
         ]);

    }

    /**
     * Search for a name.
     *
     * @param  str  $texte
     * @return \Illuminate\Http\Response
     */
    public function search($title)
    {
        $medias = Media::all();
        foreach ($medias as $media)
        {
            $media['image'] = env('BASE_URL').$media['image'];
        }
        return Media::where('title','like','%'. $title .'%')->get();
    }



}
