<?php

namespace App\Models;

use Database\Factories\CaseStudyCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudyCategory extends Model
{
    /** @use HasFactory<CaseStudyCategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<CaseStudy, $this> */
    public function caseStudies(): HasMany
    {
        return $this->hasMany(CaseStudy::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }
}
