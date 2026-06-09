<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\OfficeLocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    /** @use HasFactory<OfficeLocationFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'region', 'city', 'type', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
