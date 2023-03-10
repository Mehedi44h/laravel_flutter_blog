<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    //  get all post 
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->with('likes', function ($like) {
                return $like->where('user_id', auth()->user()->id)->select('id', 'user_id', 'post_id')->get();
            })->get()
        ], 200);
    }
    //  get single post 

    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ]);
    }
    // create post 
    public function store(Request $request)
    {
        // validate fields 
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        // skiped image 
        return response([
            'message' => 'post created',
            'post' => $post
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'post not found'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'pesmission denied'
            ], 403);
        }
        // validate fields 
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->upadate([
            'body' => $attrs['body']
        ]);

        // skiped image 
        return response([
            'message' => 'post updated',
            'post' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'post not found'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'pesmission denied'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'post deleted',
        ], 200);
    }
}
