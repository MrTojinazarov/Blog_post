<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'text',
        'img',
        'likes',    
        'dislikes',   
        'views',  
        'user_id'    
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->user_id = Auth::id();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function viewRecords()
    {
        return $this->hasMany(View::class);
    }

    public function likesOrDislikes()
    {
        return $this->hasMany(LikeOrDislike::class);
    }

    public function likes()
    {
        return $this->hasMany(LikeOrDislike::class)->where('value', 1);
    }

    public function dislikes()
    {
        return $this->hasMany(LikeOrDislike::class)->where('value', -1);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->dislikes()->count();
    }

    public function getViewsCountAttribute()
    {
        return $this->views()->count();
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
