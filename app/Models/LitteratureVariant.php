<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitteratureVariant extends Model
{
    /** @use HasFactory<\Database\Factories\LitteratureVariantFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'language', 'url', 'litterature_id'];
    //
}
