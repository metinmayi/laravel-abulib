<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LitteratureVariant extends Model
{
    protected $fillable = ['title', 'description', 'language', 'url', 'litterature_id'];
    //
}
