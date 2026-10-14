<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
        'icon', 'banner_image', 'sort_order', 'is_active', 'is_featured',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    /**
     * Allowed `category` values (the design categories / capability majors).
     */
    public const CATEGORIES = ['software_development', 'ai_automation', 'business_application', 'cloud_security', 'infrastructure_surveillance'];

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
        'infrastructure_surveillance' => 'Infrastructure & Surveillance',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * Services highlighted in the homepage core services section.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Active services picked for the "Related Services" section, in the order
     * they were picked. Falls back to other services under the same capability.
     *
     * @return Collection<int, Service>
     */
    public function relatedServices(int $limit = 6): Collection
    {
        $ids = array_map('intval', (array) ($this->content['related']['service_ids'] ?? []));

        if ($ids !== []) {
            return static::active()->whereKey($ids)->whereKeyNot($this->getKey())->get()
                ->sortBy(fn (Service $service): int => array_search($service->id, $ids, true))
                ->values()
                ->take($limit);
        }

        if (! $this->capability_id) {
            return new Collection;
        }

        return static::active()->ordered()
            ->where('capability_id', $this->capability_id)
            ->whereKeyNot($this->getKey())
            ->limit($limit)
            ->get();
    }

    /**
     * Case studies for the "Success Stories" section: the ones picked in the
     * admin, in the order picked, or else the three most recent.
     *
     * @return Collection<int, CaseStudy>
     */
    public function selectedCaseStudies(): Collection
    {
        $ids = array_map('intval', (array) ($this->content['case_studies']['ids'] ?? []));
        $query = CaseStudy::with('category')->active();

        if ($ids === []) {
            return $query->latest()->limit(3)->get();
        }

        return $query->whereKey($ids)->get()
            ->sortBy(fn (CaseStudy $caseStudy): int => array_search($caseStudy->id, $ids, true))
            ->values();
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
