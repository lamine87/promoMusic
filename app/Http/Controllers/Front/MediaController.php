<?php

namespace App\Http\Controllers\Front;
use Faker\Core\File;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Google\Service\YouTube;
// use Google\Service\YouTube;
// use Dawson\Youtube\Facades\Youtube;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;
// use Google\Service\YouTube as ServiceYouTube;
use Madcoda\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Pays;
use App\Models\Categorie;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medias = Media::where('is_online','=',1)->get();
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
        // $user = Auth::user();
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
            $request->file('image')->storeAs('public/upload', $rename);

            $img = Image::make($request->file('image')->getRealPath());

            //Dimensionner l'image
            $img->resize(500, 500);

            // Imprimer l'icon sur l'image
            $img->insert(public_path('img/icon/logo_color.png'), 'bottom-right', 5, 5);

            $img->save('storage/image/'.$rename);
        }

        Media::create([
        'url_video' => $request->url_video,
        'texte' => $request->texte,
        'title' => $request->title,
        'image' => $rename,
        'pays_id' => $request->pays
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
        $media = Media::find($id);
        $request->validate(

            [
                'url_video' => 'required | string',
                'texte' => 'required | string | max:250',
                'title' => 'required | string | max:100',
                'pays_id'=> 'required',
                'lien_facebook' => 'string',
                'lien_instagram' => 'string',
                'image' =>  'required|image|max:1999',
                'categories'=> 'required'
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

        $media->url_video = $request->url_video;
        $media->lien_facebook = $request->lien_facebook;
        $media->lien_instagram = $request->lien_instagram;
        $media->texte = $request->texte;
        $media->title = $request->title;
        $media->pays_id = $request->pays_id;
        $media->image = $uniqid.$fileName;

        $media->categories()->sync($request->categories);

        $media->save();
        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'media' => $media,
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Media::destroy($id);
    }

    /**
     * Search for a name.
     *
     * @param  str  $texte
     * @return \Illuminate\Http\Response
     */
    public function search($title)
    {
        // $medias = Media::all();
        // foreach ($medias as $media)
        // {
        //     $media['image'] = env('BASE_URL').$media['image'];
        // }
        return Media::where('title','like','%'. $title .'%')->get();
    }



}
