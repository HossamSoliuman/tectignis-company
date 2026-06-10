<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\SolutionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    /** @use HasFactory<SolutionFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'title', 'short_description', 'description', 'body', 'content',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }
}
