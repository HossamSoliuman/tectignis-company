<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
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
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Whether a setting key holds an image and should render as an upload field.
     */
    public static function isImageKey(string $key): bool
    {
        return str_contains($key, 'image') || str_contains($key, 'logo');
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

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
