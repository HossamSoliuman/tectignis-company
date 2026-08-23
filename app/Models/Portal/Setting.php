<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

/**
 * Configurable portal values (overdue thresholds, digest times, performance
 * weightages). Kept separate from the website `settings` table so operational
 * configuration and marketing content never collide.
 */
class Setting extends Model
{
    protected $table = 'portal_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function put(string $key, mixed $value, ?string $group = null): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
