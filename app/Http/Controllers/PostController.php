<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // public function index()
    // {
    //     $all = Post::with('media')->orderBy('id', 'desc')->paginate(2);
    //     $categories = Category::all();
    //     return view('admin.post',[
    //         'posts' => $all,
    //         'categories' => $categories,
    //     ]);

    // }

    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $perPage = 2;

        $offset = ($page - 1) * $perPage;

        $posts = Post::with('media')
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $total = Post::count();

        $totalPages = ceil($total / $perPage);

        $categories = Category::all();

        return view('admin.post', [
            'posts' => $posts,
            'categories' => $categories,
            'current_page' => $page,
            'total_pages' => $totalPages
        ]);

        // return response()->json([
        //     'posts' => $posts,
        //     'categories' => $categories,
        //     'current_page' => $page,
        //     'total_pages' => $totalPages,
        // ]);
        
    }


    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $post = Post::create(array_merge($data, [
            'likes' => 0,
            'dislikes' => 0,
            'views' => 0,
            'user_id' => $user->id
        ]));

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');

                $post->media()->create([
                    'url' => $path,
                    'media_type' => str_contains($file->getMimeType(), 'image') ? 'image' : 'video',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Post muvaffaqiyatli yaratildi!');
    }


    public function show($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('views');

        return response()->json([
            'post' => $post,
        ]);
    }
    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validated();

        $post->update(array_merge($data, [
            'likes' => $post->likes,
            'dislikes' => $post->dislikes,
            'views' => $post->views,
        ]));

        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $media_id) {
                $media = Media::find($media_id);
                if ($media) {
                    if (Storage::disk('public')->exists($media->url)) {
                        Storage::disk('public')->delete($media->url);
                    }
                    $media->delete();
                }
            }
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');

                $post->media()->create([
                    'url' => $path,
                    'media_type' => str_contains($file->getMimeType(), 'image') ? 'image' : 'video',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Post muvaffaqiyatli yangilandi!');
    }


    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->back();
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes');

        return response()->json([
            'success' => true,
            'message' => 'Postga like berildi.',
        ]);
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('dislikes');

        return response()->json([
            'success' => true,
            'message' => 'Postga dislike berildi.',
        ]);
    }
}
