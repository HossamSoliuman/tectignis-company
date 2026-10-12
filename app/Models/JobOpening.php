<?php

namespace App\Models;

use App\Enums\JobOpeningStatus;
use Database\Factories\JobOpeningFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * A job posting with its own public page (spec §26.9). Draft postings are
 * hidden, published ones take applications until their deadline, and closed
 * ones stay readable without the application form.
 */
class JobOpening extends Model
{
    /** @use HasFactory<JobOpeningFactory> */
    use HasFactory;

    public const EMPLOYMENT_TYPES = ['Full-time', 'Part-time', 'Contract', 'Internship', 'Remote', 'Other'];

    protected $fillable = [
        'title', 'slug', 'department', 'icon', 'location', 'employment_type', 'experience', 'skills',
        'description', 'responsibilities', 'requirements', 'benefits', 'salary', 'show_salary',
        'application_deadline', 'status', 'seo_title', 'seo_description', 'sort_order',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'draft',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'show_salary' => 'boolean',
            'application_deadline' => 'date',
            'status' => JobOpeningStatus::class,
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (JobOpening $opening): void {
            if (blank($opening->slug)) {
                $opening->slug = $opening->uniqueSlug(Str::slug(str_replace('/', ' ', $opening->title)) ?: 'job');
            }

            if ($opening->status === JobOpeningStatus::Published && $opening->published_at === null) {
                $opening->published_at = now();
            }
        });
    }

    /**
     * @return HasMany<JobApplication, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * @param  Builder<JobOpening>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Published postings whose deadline, if any, has not passed.
     *
     * @param  Builder<JobOpening>  $query
     */
    public function scopeOpen(Builder $query): void
    {
        $query->where('status', JobOpeningStatus::Published)
            ->where(function (Builder $query): void {
                $query->whereNull('application_deadline')
                    ->orWhereDate('application_deadline', '>=', today());
            });
    }

    public function isOpen(): bool
    {
        return $this->status === JobOpeningStatus::Published && ! $this->deadlinePassed();
    }

    public function deadlinePassed(): bool
    {
        return $this->application_deadline !== null && $this->application_deadline->lt(today());
    }

    public function salaryIsPublic(): bool
    {
        return $this->show_salary && filled($this->salary);
    }

    public function seoTitle(): string
    {
        return $this->seo_title ?: $this->title.' – '.($this->location ?: 'Careers');
    }

    public function seoDescription(): string
    {
        return $this->seo_description
            ?: Str::limit(trim(strip_tags((string) $this->description)), 155)
            ?: 'Apply for the '.$this->title.' role at Tectignis IT Solutions.';
    }

    /**
     * One entry per non-empty line, for fields entered as a simple list.
     *
     * @return list<string>
     */
    public static function listItems(?string $value): array
    {
        return collect(preg_split('/\R/', (string) $value))
            ->map(fn (string $line): string => trim((string) preg_replace('/^\s*[-•*]\s*/u', '', $line)))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Whether a long-text field was written as HTML rather than a plain list.
     */
    public static function isHtml(?string $value): bool
    {
        return $value !== null && $value !== strip_tags($value);
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;

        $taken = fn (string $slug): bool => static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn (Builder $query) => $query->whereKeyNot($this->getKey()))
            ->exists();

        for ($suffix = 2; $taken($slug); $suffix++) {
            $slug = $base.'-'.$suffix;
        }

        return $slug;
    }
}
