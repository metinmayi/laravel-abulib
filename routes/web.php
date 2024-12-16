<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Library;
use App\Http\Controllers\Litterature;
use App\Http\Controllers\LitteratureVariant;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [Admin::class, 'index']);
Route::get('/admin/newliterature', [Admin::class, 'newLiterature']);

Route::post('/litterature', [Litterature::class, 'uploadLitterature']);
Route::get('/litteratureVariant/{id}', [LitteratureVariant::class, 'getLitteratureBinary']);
Route::post('/litteratureVariant', [LitteratureVariant::class, 'uploadLitteratureVariant']);

Route::get('library', [Library::class, 'index']);