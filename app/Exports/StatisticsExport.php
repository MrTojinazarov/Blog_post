<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class StatisticsExport implements FromCollection
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        $data = collect();
        
        if ($this->user->hasRole('admin')) {
            $authors = User::whereHas('roles', function ($query) {
                $query->where('name', 'author');
            })->get();
            $data->push([
                'Author' => 'Author',
                'Postlar soni' => 'Postlar soni',
                'Like' => 'Likes',
                'Dislike' => 'Dislike',
                'Ko‘rishlar' => 'Ko‘rishlar',
            ]);
            foreach ($authors as $author) {
                $data->push([
                    'Author' => $author->name,
                    'Postlar soni' => $author->posts->count(),
                    'Like' => $author->posts->sum('likes'),
                    'Dislike' => $author->posts->sum('dislikes'),
                    'Ko‘rishlar' => $author->posts->sum('views'),
                ]);
            }
        }
        elseif ($this->user->hasRole('author')) {
            $data->push([
                'Author' => 'Author',
                'Postlar soni' => 'Postlar soni',
                'Like' => 'Likes',
                'Dislike' => 'Dislike',
                'Ko‘rishlar' => 'Ko‘rishlar',
            ]);
            $data->push([
                'Author' => $this->user->name,
                'Postlar soni' => $this->user->posts->count(),
                'Like' => $this->user->posts->sum('likes'),
                'Dislike' => $this->user->posts->sum('dislikes'),
                'Ko‘rishlar' => $this->user->posts->sum('views'),
            ]);
        }
    
        return $data;
    }
    
    
}
