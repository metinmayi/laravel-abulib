<?php

use App\Http\Controllers\Litterature;
use App\Http\Controllers\LitteratureVariant;
use Illuminate\Support\Facades\Route;

Route::post('/litterature', [Litterature::class, 'uploadLitterature']);
Route::get('/litteratureVariant/{id}', [LitteratureVariant::class, 'getLitteratureBinary']);
