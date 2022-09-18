<?php

namespace App\Http\Controllers\Auth;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo(Request $request)
    {
        $user = Auth::user();
        // $media = Media::find($request->id);
        if (Auth::user()->roles->pluck('name')->contains('admin')) {
            // Session::flash('Vous devez être connecter');
            return response()->json([
                'message' => 'Bienvenue Admin',
                'Admin'=>$user
              ]);

            } else if  (Auth::user()->roles->pluck('name')->contains('user')){
                return response()->json([
                    'message' => 'Bienvenue User',
                    'User'=>$user
                ]);
                // return '/show/user';

                // if (auth()->guest()) {
                //     Session::flash('Vous devez être connecter');
                //     // flash('Vous devez être connecter')->error();
                //     return $media->toJson(JSON_PRETTY_PRINT);
                // }

                $media = DB::table('media')
                // ->where('is_online','=',1)
                ->where('user_id', '=', $user->id)
                    ->orderBy('created_at', 'desc')->get();

                    return $media->toJson(JSON_PRETTY_PRINT);

            }else{
                return response()->json([
                    'message' => 'Bienvenue Super User',
                    'Super User'=>$user
                ]);
            }
    }
}
