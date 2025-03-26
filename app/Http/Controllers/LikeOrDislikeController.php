<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LikeOrDislike;
use App\Models\Post;

class LikeOrDislikeController extends Controller
{
    public function like($postId)
    {
        if (!Auth::check()) {
            return view('auth.login');
        }
    
        $post = Post::findOrFail($postId);
        $userId = Auth::id();
    
        $like = LikeOrDislike::where('post_id', $post->id)->where('user_id', $userId)->first();
    
        if ($like) {
            if ($like->value == 1) {
                $like->delete();
                $post->decrement('likes');
                return response()->json([
                    'success' => true,
                    'message' => 'Like removed.',
                    'likes' => $post->likes
                ]);
            } else {
                $like->value = 1;
                $like->save();
                $post->increment('likes');
                $post->decrement('dislikes');
            }
        } else {
            LikeOrDislike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'value' => 1,
            ]);
            $post->increment('likes');
        }
    
        return redirect()->back();
    }
    
    public function dislike($postId)
    {
        if (!Auth::check()) {
            return view('auth.login');
        }
    
        $post = Post::findOrFail($postId);
        $userId = Auth::id();
    
        $dislike = LikeOrDislike::where('post_id', $post->id)->where('user_id', $userId)->first();
    
        if ($dislike) {
            if ($dislike->value == -1) {
                $dislike->delete();
                $post->decrement('dislikes');
                return response()->json([
                    'success' => true,
                    'message' => 'Dislike removed.',
                    'dislikes' => $post->dislikes
                ]);
            } else {
                $dislike->value = -1;
                $dislike->save();
                $post->increment('dislikes');
                $post->decrement('likes'); 
            }
        } else {
            LikeOrDislike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'value' => -1,
            ]);
            $post->increment('dislikes');
        }
    
        return redirect()->back();
    }
}    