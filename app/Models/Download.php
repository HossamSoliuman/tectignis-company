<?php

namespace App\Models;

use Database\Factories\DownloadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    /** @use HasFactory<DownloadFactory> */
    use HasFactory;

    public const CATEGORIES = [
        'brochure' => 'Brochures',
        'case-study' => 'Case Studies',
        'whitepaper' => 'Whitepapers',
        'datasheet' => 'Datasheets',
        'presentation' => 'Presentations',
        'guide' => 'Guides',
    ];

    public const FILE_TYPES = ['pdf', 'doc', 'ppt', 'xls', 'zip'];

    protected $fillable = ['title', 'category', 'file_type', 'description', 'file', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }
}
