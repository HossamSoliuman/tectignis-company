<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use App\Models\Concerns\HasTranslations;
use Database\Factories\GlobalAdvantageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAdvantage extends Model
{
    /** @use HasFactory<GlobalAdvantageFactory> */
    use HasFactory, HasOrderableScopes, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations).
     *
     * @var list<string>
     */
    protected array $translatable = ['title', 'description'];

    protected $fillable = [
        'icon', 'title', 'description', 'tone', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
