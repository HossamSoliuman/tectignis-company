<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use App\Models\Concerns\HasTranslations;
use Database\Factories\IndustryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Industry extends Model
{
    /** @use HasFactory<IndustryFactory> */
    use HasContentScopes, HasFactory, HasTranslations;

    /**
     * Text served in the visitor's language (HasTranslations).
     *
     * @var list<string>
     */
    protected array $translatable = ['name', 'short_description'];

    protected $fillable = [
        'slug', 'name', 'short_description', 'description', 'body', 'content',
        'icon', 'banner_image', 'sort_order', 'is_active',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * Services this industry is attached to.
     *
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_industry');
    }
}
