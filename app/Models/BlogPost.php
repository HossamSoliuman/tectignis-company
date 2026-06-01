<?php

namespace App\Models;

use Database\Factories\BlogPostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    /** @use HasFactory<BlogPostFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'content', 'image',
        'published_at', 'is_published',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function scopeActive($query): void
    {
        $query->where('is_published', true);
    }

    public function scopePublished($query): void
    {
        $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('published_at', 'desc');
    }
}
