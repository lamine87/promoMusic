<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showUser(Request $request)
    {
        $user = Auth::user();
        $media = Media::find($request->id);
        if (auth()->guest()) {
            Session::flash('Vous devez être connecter');
            // flash('Vous devez être connecter')->error();
            return $media->toJson(JSON_PRETTY_PRINT);
        }

        $media = DB::table('media')
        ->where('is_online','=',1)
        ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')->get();

            return $media->toJson(JSON_PRETTY_PRINT);
    }

}
