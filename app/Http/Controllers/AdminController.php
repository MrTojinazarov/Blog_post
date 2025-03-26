<?php

namespace App\Http\Controllers;

use App\Exports\StatisticsExport;
use App\Models\Post;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $totalPosts = Post::count();
            $totalLikes = Post::sum('likes');
            $totalDislikes = Post::sum('dislikes');
            $totalViews = Post::sum('views');

            $authors = User::whereHas('roles', function ($query) {
                $query->where('name', 'author');
            })->withCount('posts')->get();

            return view('admin.index', compact('totalPosts', 'totalLikes', 'totalDislikes', 'totalViews', 'authors'));
        }

        elseif ($user->hasRole('author')) {
            $totalPosts = $user->posts->count();
            $totalLikes = $user->posts->sum('likes');
            $totalDislikes = $user->posts->sum('dislikes');
            $totalViews = $user->posts->sum('views');

            $posts = $user->posts;

            return view('admin.index', compact('totalPosts', 'totalLikes', 'totalDislikes', 'totalViews', 'posts'));
        }
    }    

    public function exportUserPostStatistics($user_id)
    {
        $user = User::find($user_id);
        return Excel::download(new StatisticsExport($user), 'user_post_statistics.xlsx');
    }

    public function exportUserInfoPDF($user_id)
{
    $user = User::find($user_id);

    $data = [
        'Name' => $user->name,
        'Email' => $user->email,
        'Registered' => $user->email_verified_at
    ];

    $pdf = Pdf::loadView('pdf.user_info', compact('data'));

    return $pdf->download('user_info.pdf');
}
}
