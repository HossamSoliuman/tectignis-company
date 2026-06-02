<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\StatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    /** @use HasFactory<StatFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'value', 'label', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
