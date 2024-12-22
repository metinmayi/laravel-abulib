<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\LiteratureVariantController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing.index'));

Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/newliterature', [AdminController::class, 'newLiterature']);

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('login'));
Route::post('/login', [AuthController::class, 'login']);

Route::post('/literature', [LiteratureController::class, 'upload'])->name('literature.upload');
Route::post('/literature/delete/{id}', [LiteratureController::class, 'delete'])->name('literature.delete');

Route::get('/literatureVariant/{id}', [LiteratureVariantController::class, 'getLiteratureBinary']);
Route::post('/literatureVariant/upload/{literatureId}', [LiteratureVariantController::class, 'uploadLiteratureVariant']);
Route::post('/literatureVariant/delete/{id}', [LiteratureVariantController::class, 'delete'])->name('variant.delete');

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');