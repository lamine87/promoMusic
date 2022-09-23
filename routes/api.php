<?php

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\MediaController;
use App\Http\Controllers\Gestion\ActuController;
use App\Http\Controllers\Post\CommentController;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Gestion\GestionRedirectController;

// use Youtube;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// return $request->user();
//});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// _Protected Routes_____Protected Routes_______Protected Routes_______Protected Routes_

// Route::group(['middleware'=>['Api'=>'auth:sanctum']],function(){
//}


// Route::group(['middleware','Auth'=> ['auth:sanctum']], function () {
Route::middleware(['auth:sanctum'])->group(function(){

    // Auth::routes();

    Route::get("/user",[AuthController::class,'users']);
    // Logout route
    Route::post('/logout',[AuthController::class,'logout']);

    // Adding route Medias
    Route::post('/addmedia',[MediaController::class, 'store']);

     // Edit route Medias
    Route::get('/edit/media/{id}',[MediaController::class, 'show']);

    //  Update route media
    Route::post('/update/media/{id}',[MediaController::class, 'update']);

     // Delete route Medias
    Route::post('/destroy/media/{id}',[MediaController::class, 'destroy']);

    // Route Admin or SuperUser or Users
    Route::post('/admin',[GestionRedirectController::class,'loginAdmin']);

    // Adding route Actualité
    Route::post('/add/actu',[ActuController::class, 'store']);

     // Edit route Actualité
    Route::get('/edit/actu/{id}',[ActuController::class, 'show']);

    //  Update route Actualité
    Route::post('/update/actu/{id}',[ActuController::class, 'update']);

     // Delete route Actualité
    Route::post('/destroy/actu/{id}',[ActuController::class, 'destroy']);

    //////////////_Protected Route///////////// Post Comment /////////////_Protected Route//
    // Adding route Comment
    Route::post('/add/comment/{media}', [CommentController::class, 'store']);

     // Edit route Comment
    Route::get('/edit/comment/{id}', [CommentController::class, 'show']);

      //  Update route Comment
    Route::post('/update/comment/{id}',[CommentController::class, 'update']);

    //  Delete route Comment
    Route::post('/destroy/comment/{id}',[CommentController::class, 'destroy']);

     // Adding route Reply
     Route::post('/add/reply/{comment}', [PostController::class, 'store']);

     // Edit route Post
    Route::get('/edit/post/{id}', [PostController::class, 'show']);

      //  Update route Comment
    Route::post('/update/post/{id}',[PostController::class, 'update']);

    //  Delete route post
    Route::post('/destroy/post/{id}',[PostController::class, 'destroy']);

    Route::get('/youtube',[MediaController::class, 'youtubeur']);

    Route::post('/youtubevideo',[MediaController::class, 'videoInsert']);

    Route::get('/show/user', [HomeController::class, 'showUser']);

});


// _____Public Routes_____Public Routes_______Public Routes_______Public Routes_

// Route Authentication
Route::post("/login",[AuthController::class, 'login']);

// Route register user
Route::post("/register",[AuthController::class,'register']);

 // Display route Media
Route::get('/media',[MediaController::class, 'index']);

 // Search route media
Route::get('/media/search/{title}',[MediaController::class, 'search']);

 // Register route
Route::post('/register',[AuthController::class, 'register']);

// Category route
Route::get('/categorie',[PageController::class, 'categorie']);

Route::get('/categorie/media/{id}',[PageController::class, 'voirCategorie']);


Route::get('/pays',[PageController::class, 'continent']);
// Country route
Route::get('/pays/media/{id}',[PageController::class, 'mediaByContinent']);

// Display route Actu
Route::get('/actualite', [ActuController::class, 'actu']);

//Public//////////////////////// Post Comment ////////////////////////Public//
     // Route comment index
Route::get('/comment/index', [CommentController::class, 'index']);

  // Route post index
  //Route::get('/post/index', [PostController::class, 'index']);
Route::get('/youtube',[MediaController::class, 'youtubeur']);


