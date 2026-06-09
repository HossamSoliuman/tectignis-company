<?php

namespace App\Models;

use App\Models\Concerns\HasOrderableScopes;
use Database\Factories\ProcessStepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    /** @use HasFactory<ProcessStepFactory> */
    use HasFactory, HasOrderableScopes;

    protected $fillable = [
        'icon', 'title', 'description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
