<?php

namespace App\Models;

use App\Enums\CaseStudyStatus;
use App\Enums\ProjectStatus;
use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasTranslations;
use Database\Factories\CaseStudyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A portfolio project. Only published projects reach the public site; the
 * detail fields (challenge, solution, modules, stack, gallery, outcome) build
 * the case-study page.
 */
class CaseStudy extends Model
{
    /** @use HasFactory<CaseStudyFactory> */
    use FlushesSiteCache, HasFactory, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations).
     *
     * @var list<string>
     */
    protected array $translatable = ['title', 'short_description', 'features'];

    protected $fillable = [
        'slug', 'status', 'project_status', 'title', 'client_name', 'case_study_category_id',
        'industry_id', 'service_id', 'location', 'project_date', 'theme', 'short_description',
        'challenge', 'solution', 'modules', 'technologies', 'outcome', 'image', 'image_alt',
        'gallery', 'testimonial_id', 'icon', 'features', 'content', 'sort_order', 'is_featured',
        'seo_title', 'seo_description',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'draft',
        'project_status' => 'completed',
    ];

    protected function casts(): array
    {
        return [
            'status' => CaseStudyStatus::class,
            'project_status' => ProjectStatus::class,
            'project_date' => 'date',
            'sort_order' => 'integer',
            'is_featured' => 'boolean',
            'features' => 'array',
            'modules' => 'array',
            'technologies' => 'array',
            'gallery' => 'array',
        ];
    }

    /** @return BelongsTo<CaseStudyCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseStudyCategory::class, 'case_study_category_id');
    }

    /** @return BelongsTo<Industry, $this> */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    /**
     * The service page this project is proof for.
     *
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * The client's approved testimonial for this project.
     *
     * @return BelongsTo<Testimonial, $this>
     */
    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    /**
     * Projects visible on the public site.
     *
     * @param  Builder<self>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', CaseStudyStatus::Published);
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function isPublished(): bool
    {
        return $this->status === CaseStudyStatus::Published;
    }

    public function isOngoing(): bool
    {
        return $this->project_status === ProjectStatus::Ongoing;
    }

    /**
     * Alt text for the cover image, falling back to the project title.
     */
    public function coverAlt(): string
    {
        return $this->image_alt ?: $this->title;
    }

    /**
     * Screenshots with an image, each with alt text.
     *
     * @return list<array{image: string, alt: string}>
     */
    public function galleryImages(): array
    {
        return collect($this->gallery ?? [])
            ->filter(fn (mixed $item): bool => is_array($item) && filled($item['image'] ?? null))
            ->map(fn (array $item): array => ['image' => $item['image'], 'alt' => ($item['alt'] ?? null) ?: $this->title.' screenshot'])
            ->values()
            ->all();
    }
}
