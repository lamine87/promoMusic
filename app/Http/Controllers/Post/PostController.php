<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function store(Request $request, Comment $comment)
    {
    	request()->validate([
            'content'=>'required|min:3',
        ]);

        $reply = new Comment();
        $reply->content = request('content');
        $reply->user_id = auth()->user()->id;
        $reply->parent_id = request('comment');
        //$reply = Media::find($request->get('media'));
        $comment->commentables()->save($reply);

        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'comment' => $reply,
         ]);

    }


    public function index()
    {
        $post = Post::all();

        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'post' => $post,
         ]);
    }





    public function show($id)
    {
    	$post = Post::find($id);
        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'post' => $post,
         ]);
    }


    public function update($id)
    {
        $post = Post::find($id);
    	request()->validate([
            'body'=>'required|min:3',
        ]);

        $post->body = request('body');
        $post->save();

        return response()->json([JSON_PRETTY_PRINT,
        'message'=>'successful!',
        'status'=>true,
        'post' => $post,
         ]);
    }


    public function destroy($id)
    {
        return Post::destroy($id);
    }

}
