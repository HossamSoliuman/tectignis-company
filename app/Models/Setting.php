<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
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
     * All settings as a key => value map. Memoized per-request via once() and
     * cached across requests; both layers are flushed by FlushesSiteCache when
     * any setting is saved or deleted.
     *
     * @return Collection<string, string|null>
     */
    public static function values(): Collection
    {
        return once(fn () => Cache::rememberForever('site.settings', fn () => static::pluck('value', 'key')));
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
