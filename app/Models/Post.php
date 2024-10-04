<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'thumbnail', 'color', 'slug', 
        'category_id', 'content', 'tags', 'private'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }

    // Many-to-many relationship with User model
    public function authors()
    {
        return $this->belongsToMany(User::class, 'post_user')->withPivot(['order'])->withTimestamps();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
