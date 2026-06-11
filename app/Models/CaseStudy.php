<?php

namespace App\Models;

use Database\Factories\CaseStudyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudy extends Model
{
    /** @use HasFactory<CaseStudyFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'case_study_category_id', 'theme', 'short_description',
        'image', 'icon', 'features', 'content', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'features' => 'array',
        ];
    }

    /** @return BelongsTo<CaseStudyCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseStudyCategory::class, 'case_study_category_id');
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('sort_order');
    }
}
