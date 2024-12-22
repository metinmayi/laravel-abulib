<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Library;
use App\Http\Controllers\Litterature;
use App\Http\Controllers\LitteratureVariant;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing.index'));

Route::get('/admin', [Admin::class, 'index']);
Route::get('/admin/newliterature', [Admin::class, 'newLiterature']);

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('login'));
Route::post('/login', [AuthController::class, 'login']);

Route::post('/literature', [Litterature::class, 'upload'])->name('literature.upload');
Route::post('/literature/delete/{id}', [Litterature::class, 'delete'])->name('literature.delete');

Route::get('/litteratureVariant/{id}', [LitteratureVariant::class, 'getLitteratureBinary']);
Route::post('/litteratureVariant', [LitteratureVariant::class, 'uploadLitteratureVariant']);
Route::delete('/litteratureVariant/delete/{id}', [LitteratureVariant::class, 'delete'])->name('variant.delete');

Route::get('/library', [Library::class, 'index'])->name('library.index');