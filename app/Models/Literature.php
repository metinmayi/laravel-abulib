<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Literature extends Model
{
    /** @use HasFactory<\Database\Factories\LiteratureFactory> */
    use HasFactory;

    public const CATEGORIES = ['poem', 'research', 'book', 'article', 'report'];

    protected $fillable = ['category'];

    /**
     * Get the variants for the literature.
     * @return HasMany<Variant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }
}
