<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Literature extends Model
{
    /** @use HasFactory<\Database\Factories\LiteratureFactory> */
    use HasFactory;

    public const CATEGORIES = ['poem', 'research', 'book', 'article'];
    public const LANGUAGES = ['english', 'kurdish', 'arabic', 'swedish'];

    protected $fillable = ['category'];

    /**
     * Get the variants for the literature.
     * @return HasMany<LiteratureVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(LiteratureVariant::class);
    }
}
