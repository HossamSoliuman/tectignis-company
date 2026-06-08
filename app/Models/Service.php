<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'capability_id', 'category', 'title', 'short_description', 'description', 'body', 'content',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    /**
     * Allowed `category` values (the design categories / capability majors).
     */
    public const CATEGORIES = ['software_development', 'ai_automation', 'business_application', 'cloud_security'];

    /**
     * Human-readable labels for the design categories (single source of truth
     * for the admin form and the public services index grouping).
     *
     * @var array<string, string>
     */
    public const CATEGORY_LABELS = [
        'software_development' => 'Software Development',
        'ai_automation' => 'AI & Automation',
        'business_application' => 'Business Application',
        'cloud_security' => 'Cloud & Security',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * The capability this service belongs to. Capabilities act as the headers
     * the service is grouped under in the public header mega-menu.
     *
     * @return BelongsTo<Capability, $this>
     */
    public function capability(): BelongsTo
    {
        return $this->belongsTo(Capability::class);
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
