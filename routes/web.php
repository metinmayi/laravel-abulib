<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\ReadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing.index'))->name('landingPage');

Route::group(['middleware' => 'auth'], function () {
  Route::resources([
    'literature' => LiteratureController::class,
    'variant' => VariantController::class,
  ]);

  Route::get('/admin/newvariant/{id}', [AdminController::class, 'newVariant'])->name('admin.newvariantpage');
});

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('login'));
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Should be moved to reader
Route::get('/literatureVariant/{id}', [VariantController::class, 'getLiteratureBinary']);

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

Route::get('/reader/{variantId}', [ReadController::class, 'index'])->name('read.index');
Route::get('/reader/variant/{id}', [ReadController::class, 'getLiteratureBinary'])->name('read.getLiteratureBinary');