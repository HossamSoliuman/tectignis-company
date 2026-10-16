<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A batch downloaded from the Translations tab. It records the fingerprint of
 * each item's English at download time, so an import can tell which items
 * were edited in English while the batch was being translated.
 */
class TranslationExport extends Model
{
    protected $fillable = ['export_id', 'languages', 'items', 'created_by'];

    protected function casts(): array
    {
        return [
            'languages' => 'array',
            'items' => 'array',
        ];
    }

    /**
     * The next ID for today's exports, e.g. "2026-10-16-03".
     */
    public static function nextExportId(): string
    {
        $prefix = now()->format('Y-m-d');
        $count = static::where('export_id', 'like', $prefix.'-%')->count();

        return $prefix.'-'.str_pad((string) ($count + 1), 2, '0', STR_PAD_LEFT);
    }

    /**
     * The fingerprint of the item's English when it was exported, or null if
     * the item isn't part of this export.
     */
    public function sourceHashFor(string $itemId): ?string
    {
        return $this->items[$itemId]['source_hash'] ?? null;
    }

    /**
     * The languages the item was exported for.
     *
     * @return list<string>
     */
    public function languagesFor(string $itemId): array
    {
        return $this->items[$itemId]['languages'] ?? [];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
