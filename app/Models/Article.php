<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'meta_description', 'category', 'type', 'images', 'keywords', 'published', 'auther', 'likes_count', 'likes'];
    protected $hidden = ['meta_description', 'keywords'];
    protected $casts = ['keywords' => 'array', 'images' => 'array'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
