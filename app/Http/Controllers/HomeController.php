<?php

namespace App\Http\Controllers;

use App\Models\User;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (auth()->guest()) {
            Session::flash('Vous devez être connecter');
            // flash('Vous devez être connecter')->error();
            return redirect('login');
        }
        $user = Auth::user();
        $media = DB::table('media')->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')->get();

            return $media->toJson(JSON_PRETTY_PRINT);
    }

}
