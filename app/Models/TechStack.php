<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\TechStackFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechStack extends Model
{
    /** @use HasFactory<TechStackFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'name', 'logo', 'sort_order', 'is_active', 'show_on_home',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_on_home' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeShownOnHome($query): void
    {
        $query->where('show_on_home', true);
    }

    /**
     * Services this technology is attached to.
     *
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}
