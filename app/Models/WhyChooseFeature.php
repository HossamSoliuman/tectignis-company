<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\WhyChooseFeatureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseFeature extends Model
{
    /** @use HasFactory<WhyChooseFeatureFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'icon', 'title', 'text', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
