<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A software product Tectignis builds and runs itself, shown in the
 * homepage's Our Products section.
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasOrderableScopes, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations). The name is a
     * brand and stays the same in every language.
     *
     * @var list<string>
     */
    protected array $translatable = ['description', 'features'];

    protected $fillable = [
        'name', 'description', 'features', 'icon', 'url',
        'case_study_id', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
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
}
