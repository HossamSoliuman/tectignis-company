<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\CapabilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Capability extends Model
{
    /** @use HasFactory<CapabilityFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'category', 'title', 'short_description', 'description', 'body',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    /**
     * Allowed `category` values (the capability "majors").
     */
    public const CATEGORIES = ['ai_automation', 'business_application', 'software_development', 'cloud_security'];

    /**
     * Human-readable labels for the capability majors, in the order they
     * should appear as columns in the header mega-menu. Single source of truth
     * for the admin form and the public grouping.
     *
     * @var array<string, string>
     */
    public const CATEGORY_LABELS = [
        'ai_automation' => 'AI & Automation',
        'business_application' => 'Business Application',
        'software_development' => 'Software Development',
        'cloud_security' => 'Cloud & Security',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Services grouped under this capability, ordered for the header mega-menu.
     *
     * @return HasMany<Service, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class)->ordered();
    }
}
