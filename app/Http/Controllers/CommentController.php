<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // get all comments 
    public function index($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'post not found'
            ], 403);
        }
        return response([
            'comments' => $post->comments()->with('user:id,name,image')->get()

        ], 200);
    }

    // create a comment 
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'post not found'
            ], 403);
        }
        // validate comm 
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);
        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);
        return response([
            'message' => 'Comment created'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied'
            ], 403);
        }
        // validate comment 
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);
        $comment->update([
            'comment' => $attrs['comment']
        ]);
        return response([
            'message' => 'Comment updated'
        ], 200);
    }

    // delete cmomment f
    public function destroy(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied'
            ], 403);
        }
        // validate comment 
        $comment->delete();

        return response([
            'message' => 'Comment deleted'
        ], 200);
    }
}
