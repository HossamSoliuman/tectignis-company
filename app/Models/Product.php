<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasOrderableScopes;
use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * A software product Tectignis builds and runs itself, shown in the
 * homepage's Our Products section and showcased with its screenshots and
 * videos on the Products page.
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use FlushesSiteCache, HasFactory, HasOrderableScopes, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations). The name is a
     * brand and stays the same in every language.
     *
     * @var list<string>
     */
    protected array $translatable = ['description', 'features'];

    protected $fillable = [
        'name', 'description', 'features', 'gallery', 'videos', 'icon', 'url',
        'case_study_id', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'gallery' => 'array',
            'videos' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<CaseStudy, $this>
     */
    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }

    /**
     * The product's section id on the Products page, e.g. "tectignis-qr".
     */
    public function anchor(): string
    {
        return Str::slug($this->name) ?: 'product-'.$this->getKey();
    }

    /**
     * The website without its scheme, as shown in the preview's address bar.
     */
    public function displayUrl(): ?string
    {
        return $this->url ? rtrim(preg_replace('#^https?://(www\.)?#i', '', $this->url), '/') : null;
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
            ->map(fn (array $item): array => ['image' => $item['image'], 'alt' => ($item['alt'] ?? null) ?: $this->name.' screenshot'])
            ->values()
            ->all();
    }

    /**
     * Playable videos: YouTube and Vimeo links as privacy-friendly embeds and
     * uploaded files as native video. Links from other sites are skipped.
     *
     * @return list<array{type: string, src: string, title: string, thumbnail: ?string}>
     */
    public function videoItems(): array
    {
        $items = [];

        foreach ($this->videos ?? [] as $video) {
            $source = is_array($video) ? trim((string) ($video['source'] ?? '')) : '';

            if ($source === '') {
                continue;
            }

            $title = ($video['title'] ?? null) ?: $this->name.' video';
            $item = self::embedFor($source);

            if ($item === null && ! Str::startsWith($source, ['http://', 'https://'])) {
                $item = ['type' => 'file', 'src' => asset('uploads/'.$source), 'thumbnail' => null];
            }

            if ($item !== null) {
                $items[] = [...$item, 'title' => $title];
            }
        }

        return $items;
    }

    /**
     * The embed for a YouTube or Vimeo link, or null for any other link.
     *
     * @return array{type: string, src: string, thumbnail: ?string}|null
     */
    public static function embedFor(string $url): ?array
    {
        if (preg_match('#(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([\w-]{11})#i', $url, $match)) {
            return [
                'type' => 'youtube',
                'src' => 'https://www.youtube-nocookie.com/embed/'.$match[1].'?autoplay=1&rel=0&modestbranding=1',
                'thumbnail' => 'https://i.ytimg.com/vi/'.$match[1].'/hqdefault.jpg',
            ];
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $url, $match)) {
            return [
                'type' => 'vimeo',
                'src' => 'https://player.vimeo.com/video/'.$match[1].'?autoplay=1&dnt=1',
                'thumbnail' => null,
            ];
        }

        return null;
    }
}
