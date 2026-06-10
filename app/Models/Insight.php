<?php

namespace App\Models;

use Database\Factories\InsightFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insight extends Model
{
    /** @use HasFactory<InsightFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'topic', 'excerpt', 'content', 'image', 'author', 'sort_order',
        'published_at', 'is_published',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished($query): void
    {
        $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('sort_order')->orderByDesc('published_at');
    }
}
