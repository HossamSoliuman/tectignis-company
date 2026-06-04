<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'category', 'title', 'short_description', 'description', 'body', 'content',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    /**
     * Allowed `category` values (reconciled to the three design categories).
     */
    public const CATEGORIES = ['software_development', 'ai_automation', 'business_application'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * Technologies shown in the "Technologies We Use" section (Section E).
     *
     * @return BelongsToMany<TechStack, $this>
     */
    public function techStacks(): BelongsToMany
    {
        return $this->belongsToMany(TechStack::class);
    }

    /**
     * Industries shown in the "Industries We Serve" section (Section F).
     *
     * @return BelongsToMany<Industry, $this>
     */
    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class, 'service_industry');
    }
}
