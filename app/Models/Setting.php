<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Once;

class Setting extends Model
{
    use FlushesSiteCache;

    protected $fillable = ['key', 'value', 'group'];

    /**
     * Legacy asset directories for image settings whose seeded values are
     * filenames bundled with the template rather than uploaded files.
     *
     * @var array<string, string>
     */
    public const IMAGE_DIRS = [
        'hero_image' => 'assets/images/hero',
        'what_we_offer_image' => 'assets/images/banners',
        'tech_service_image' => 'assets/images/banners',
        'site_logo' => 'assets/images/logo',
        'site_logo_dark' => 'assets/images/logo',
        'site_favicon' => 'assets/images/logo',
    ];

    /**
     * Text settings shown on the translated pages. In another language they're
     * served from the `translations` table (Translations tab), falling back to
     * the English value; every other setting is the same in all languages.
     *
     * @var list<string>
     */
    public const TRANSLATABLE_KEYS = [
        'hero_sub_heading', 'hero_heading_line1', 'hero_heading_line2', 'hero_info_heading', 'hero_btn_primary', 'hero_btn_secondary',
        'trust_heading',
        'core_pretitle', 'core_heading', 'core_heading_highlight',
        'pillars_pretitle', 'pillars_heading', 'pillars_subtitle',
        'why_badge', 'why_heading', 'why_heading_highlight', 'why_subtitle',
        'cs_badge', 'cs_heading', 'cs_subtitle',
        'ind_pretitle', 'ind_heading_line1', 'ind_heading_line2', 'ind_subtitle',
        'process_pretitle', 'process_heading', 'process_subtitle',
        'tech_sub_heading', 'tech_heading', 'tech_heading_highlight',
        'gp_pretitle', 'gp_heading', 'gp_heading_highlight', 'gp_subtitle',
        'cta_overline', 'cta_heading', 'cta_heading_highlight', 'cta_heading_suffix', 'cta_subheading', 'cta_btn_primary', 'cta_btn_secondary',
        'home_meta_title', 'home_meta_description', 'home_og_title', 'home_og_description',
        'business_hours',
        'legal_privacy-policy', 'legal_terms-and-conditions',
    ];

    /**
     * Also drop the per-request once() memo behind values(), so a setting saved
     * earlier in the same request is read back fresh.
     */
    protected static function booted(): void
    {
        static::saved(static fn () => Once::flush());
        static::deleted(static fn () => Once::flush());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::values()->get($key) ?? $default;
    }

    /**
     * Decode a JSON-encoded setting value into an array, returning $default when
     * the setting is missing or does not decode to a non-empty array.
     *
     * @param  array<mixed>  $default
     * @return array<mixed>
     */
    public static function json(string $key, array $default = []): array
    {
        $decoded = json_decode((string) static::get($key), true);

        return is_array($decoded) && $decoded !== [] ? $decoded : $default;
    }

    /**
     * All settings as a key => value map, in the current language. Memoized
     * per-request via once() and cached across requests per language; both
     * layers are flushed by FlushesSiteCache when any setting or translation
     * is saved or deleted.
     *
     * @return Collection<string, string|null>
     */
    public static function values(): Collection
    {
        $locale = Locales::current();
        $cacheKey = Locales::cacheKey('site.settings', $locale);

        return once(fn () => Cache::rememberForever($cacheKey, fn () => static::loadValues($locale)));
    }

    /**
     * @return Collection<string, string|null>
     */
    private static function loadValues(string $locale): Collection
    {
        $values = static::query()->pluck('value', 'key');

        if (Locales::isDefault($locale)) {
            return $values;
        }

        $translated = Translation::query()
            ->join('settings', 'settings.id', '=', 'translations.translatable_id')
            ->where('translations.translatable_type', (new static)->getMorphClass())
            ->where('translations.field', 'value')
            ->where('translations.locale', $locale)
            ->whereIn('settings.key', self::TRANSLATABLE_KEYS)
            ->pluck('translations.value', 'settings.key');

        return $values->merge($translated);
    }

    /**
     * Whether a setting key holds an image and should render as an upload field.
     */
    public static function isImageKey(string $key): bool
    {
        return str_contains($key, 'image') || str_contains($key, 'logo') || str_contains($key, 'favicon');
    }

    /**
     * Resolve an image setting value to a public URL. Uploaded files live in
     * public/uploads; seeded template filenames fall back to their legacy dir.
     */
    public static function imageUrl(?string $value, ?string $key = null): ?string
    {
        if (! $value) {
            return null;
        }

        if (is_file(public_path('uploads/'.$value))) {
            return asset('uploads/'.$value);
        }

        $dir = self::IMAGE_DIRS[$key] ?? 'assets/images';

        return asset($dir.'/'.$value);
    }

    /**
     * Intrinsic [width, height] of an image setting, used to reserve layout
     * space (spec §13). Resolves the file the same way as imageUrl().
     *
     * @return array{0: int, 1: int}|null
     */
    public static function imageSize(?string $value, ?string $key = null): ?array
    {
        if (! $value) {
            return null;
        }

        $path = is_file(public_path('uploads/'.$value))
            ? public_path('uploads/'.$value)
            : public_path((self::IMAGE_DIRS[$key] ?? 'assets/images').'/'.$value);

        $size = is_file($path) ? @getimagesize($path) : false;

        return $size ? [$size[0], $size[1]] : null;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
