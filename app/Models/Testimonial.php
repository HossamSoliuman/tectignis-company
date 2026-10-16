<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasTranslations;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use FlushesSiteCache, HasFactory, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations).
     *
     * @var list<string>
     */
    protected array $translatable = ['quote'];

    protected $fillable = ['name', 'quote', 'rating', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'rating' => 'integer',
        ];
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
