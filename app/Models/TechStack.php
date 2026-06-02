<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\TechStackFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechStack extends Model
{
    /** @use HasFactory<TechStackFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'name', 'logo', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
