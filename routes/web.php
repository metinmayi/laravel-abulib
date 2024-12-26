<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\LiteratureVariantController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing.index'))->name('landingPage');

Route::group(['middleware' => 'auth'], function () {
  Route::get('/admin/newliterature', [AdminController::class, 'newLiterature'])->name('admin.newliteraturepage');
  Route::get('/admin/newvariant', [AdminController::class, 'newVariant'])->name('admin.newvariantpage');
  Route::get('/admin/editvariant/{id}', [AdminController::class, 'editVariant'])->name('admin.editvariantpage');

  Route::post('/literature', [LiteratureController::class, 'upload'])->name('literature.upload');
  Route::post('/literature/delete/{id}', [LiteratureController::class, 'delete'])->name('literature.delete');

  Route::post('/literatureVariant/upload/{literatureId}', [LiteratureVariantController::class, 'upload'])->name('variant-upload');
  Route::post('/literatureVariant/delete/{id}', [LiteratureVariantController::class, 'delete'])->name('variant.delete');
  Route::post('/literatureVariant/edit/{id}', [LiteratureVariantController::class, 'edit'])->name('variant.edit');
});

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('login'));
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/literatureVariant/{id}', [LiteratureVariantController::class, 'getLiteratureBinary']);

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
