<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Litterature extends Model
{
    /** @use HasFactory<\Database\Factories\LitteratureFactory> */
    use HasFactory;

    protected $fillable = ['category'];
    // abc
}
