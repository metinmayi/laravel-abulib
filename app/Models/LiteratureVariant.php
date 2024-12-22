<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteratureVariant extends Model
{
    /** @use HasFactory<\Database\Factories\LiteratureVariantFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'language', 'url', 'literature_id'];
}
