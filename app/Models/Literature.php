<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Literature extends Model
{
    /** @use HasFactory<\Database\Factories\LiteratureFactory> */
    use HasFactory;

    public const CATEGORIES = ['poem', 'research', 'book', 'article'];
    public const LANGUAGES = ['english', 'kurdish', 'arabic', 'swedish'];

    protected $fillable = ['category'];
}
