<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\LiteratureVariantController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing.index'))->name('landingPage');

Route::get('/admin', [AdminController::class, 'index'])->middleware('auth');
Route::get('/admin/newliterature', [AdminController::class, 'newLiterature'])->middleware('auth');

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('login'));
Route::post('/login', [AuthController::class, 'login']);

Route::post('/literature', [LiteratureController::class, 'upload'])->name('literature.upload')->middleware('auth');
Route::post('/literature/delete/{id}', [LiteratureController::class, 'delete'])->name('literature.delete')->middleware('auth');

Route::get('/literatureVariant/{id}', [LiteratureVariantController::class, 'getLiteratureBinary']);
Route::post('/literatureVariant/upload/{literatureId}', [LiteratureVariantController::class, 'uploadLiteratureVariant'])->name('variant-upload')->middleware('auth');
Route::post('/literatureVariant/delete/{id}', [LiteratureVariantController::class, 'delete'])->name('variant.delete')->middleware('auth');
Route::post('/literatureVariant/update/{id}', [LiteratureVariantController::class, 'update'])->name('variant.update')->middleware('auth');
Route::get('/literatureVariant/edit/{id}', [LiteratureVariantController::class, 'edit'])->name('variant.editPage')->middleware('auth');

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');