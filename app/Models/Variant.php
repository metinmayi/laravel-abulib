<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends Model
{
    /** @use HasFactory<\Database\Factories\VariantFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'language', 'url', 'literature_id'];

    /**
     * Get the literature that owns the Variant
     * @return BelongsTo<Literature, $this>
     */
    public function literature(): BelongsTo
    {
        return $this->belongsTo(Literature::class);
    }
}
