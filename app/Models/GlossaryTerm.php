<?php

namespace App\Models;

use Database\Factories\GlossaryTermFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A fixed translation of an English term in one language. The Translations
 * tab adds each language's terms to the prompt, so wording stays the same
 * across batches.
 */
class GlossaryTerm extends Model
{
    /** @use HasFactory<GlossaryTermFactory> */
    use HasFactory;

    protected $fillable = ['locale', 'term', 'translation'];
}
