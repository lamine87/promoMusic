<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GestionRedirectController extends Controller
{
    public function loginAdmin(Request $request)
    {
            $user = Auth::user();

            if ($user->hasRole('Admin')) {

                //Si le user est admin
                 return response()->json([
                  'message' => 'Bienvenue Admin',
                     'user' =>$user

                ]);
                //return $user->toJson(JSON_PRETTY_PRINT);

            } else if ($user->hasRole('SuperUser')) {
                // Si le user est SuperUser
                return response()->json([
                    'message' => 'Bienvenue Super User',
                     'user' =>$user
                ]);
            }else if ($user->hasRole('Users')) {
                // Si le user est Users
                return response()->json([
                    'message' => 'Bienvenue User',
                    'user' =>$user
                ]);
            }

         else {
             return response()->json([
                 'message' => 'impossible de vous identifier'
             ]);

        }
    }


}
