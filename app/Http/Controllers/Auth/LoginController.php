<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function redirectTo()
    {
        if (Auth::user()->roles->pluck('name')->contains('admin')) {
            // Session::flash('Vous devez être connecter');
            return response()->json([
                'message' => 'Bienvenue Admin',
                //    'user' =>$user
              ]);

            } else if  (Auth::user()->roles->pluck('name')->contains('admin')){
                return response()->json([
                    'message' => 'Bienvenue User',
                    //  'user' =>$user
                ]);

            }else{
                return response()->json([
                    'message' => 'Bienvenue Super User',
                    //  'user' =>$user
                ]);
            }
    }
}
