<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\token;
use Illuminate\Support\Facades\Cookie;
use Intervention\Image\ImageManagerStatic as Image;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $user = Auth::user();
        $fields = $request->validate([
            'name' => 'required', 'string', 'max: 100',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required|string|confirmed|min:8',
            'lien_facebook' => ['string'],
            'lien_instagram' => ['string'],
            'avatar' => ['image'],
        ]);

        if ($request->hasFile('avatar')) {
            $uniqid = uniqid();

            // Recuperer le nom de l'image saisi par l'utilisateur
            $fileName = $request->file('avatar')->getClientOriginalName();

            // Renommer le nom de l'image
            $reName = str_replace('','_',$uniqid).'-'.date('d-m-Y-H-i-').$fileName;

            $img = Image::make($request->file('avatar')->getRealPath());

            //Dimensionner l'image
            $img->resize(200, 180);

            $img->save('storage/avatar/'.$reName);
        }

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'lien_facebook' => $fields['lien_facebook'],
            'lien_instagram' => $fields['lien_instagram'],
            'avatar' => $reName,
        ]);
        $role = Role::select('id')->where('name','user')->first();
        $user->roles()->attach($role);

        //create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([JSON_PRETTY_PRINT,
            'status' => true,
            'message' => 'registered successfully!',
            'user' => $user,
            'token' => $token,
            'avatar' => $reName,
            'token_type' => 'Bearer',
        ]);

    }



        public function users(){

            $user = User::all();
                return response()->json([JSON_PRETTY_PRINT,
                'user' => $user,
              ]);
        }


    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        //check email
        $user = User::where('email',$fields['email'])->first();

        //check password
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response(['status'=>false,
                'message'=>'Email et/ou mot de passe incorrect(s)'], 401);
        }
        //create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([JSON_PRETTY_PRINT,
            'message'=>'Login successful!',
            'status'=>true,
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            // 'token_expires_at'=> $token->token->expires_at,
        ]);
        // return response($response,201);

    }

    public function logout(Request $request)
    {
        // auth()->user()->tokens()->delete();
        // return response()->json([
        //     'message' => 'Successfully logged out'
        // ]);

        $this->validate($request, [
            'allDevice' => 'required'
        ]);

        /**
         * @var user $user
         */
        $user = Auth::user();
        if ($request->allDevice) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
            return response(['message' => 'Déconnecté de tous les appareils !'], 200);
        }

        $userToken = $user->token();
        $userToken->delete();
        return response(['message' => 'Deconnexion réussie !'], 200);
    }


}


