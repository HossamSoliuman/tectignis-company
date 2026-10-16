<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Database\Factories\TranslationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Once;

/**
 * One field of one record in one language. `field` is a column name or, for
 * nested content, a path such as `content.hero.heading`. `source_hash` is the
 * fingerprint of the English text it was translated from: when the English
 * changes, the translation is outdated until a new one is imported.
 */
class Translation extends Model
{
    /** @use HasFactory<TranslationFactory> */
    use FlushesSiteCache, HasFactory;

    protected $fillable = [
        'translatable_type', 'translatable_id', 'field', 'locale', 'value', 'source_hash', 'imported_by',
    ];

    /**
     * Also drop the per-request once() memos (settings, company stats), so a
     * translation saved earlier in the same request is read back fresh.
     */
    protected static function booted(): void
    {
        static::saved(static fn () => Once::flush());
        static::deleted(static fn () => Once::flush());
    }

    /**
     * The fingerprint stored with a translation, of the English it came from.
     */
    public static function hash(?string $source): string
    {
        return sha1((string) $source);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function importer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
