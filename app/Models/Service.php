<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'category', 'title', 'short_description', 'description', 'body',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
