<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\VariantController;
use App\Http\Middleware\SetUserLocale;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => SetUserLocale::class], function () {

    Route::get('/', fn () => view('landing.index'))->name('landingPage')->middleware([SetUserLocale::class]);

    Route::group(['middleware' => 'auth'], function () {
        Route::resources([
          'literature' => LiteratureController::class,
          'variant' => VariantController::class,
        ]);

        Route::get('/admin/newvariant/{id}', [AdminController::class, 'newVariant'])->name('admin.newvariantpage');
    });

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', fn () => view('login'));
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // Library Page
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

    // Reader Page
    Route::get('/reader/{variantId}', [ReaderController::class, 'index'])->name('read.index');
    Route::get('/reader/variant/{id}', [ReaderController::class, 'getLiteratureBinary'])->name('read.getLiteratureBinary');
    Route::get('/reader/variantFile/{id}', [ReaderController::class, 'getLiteratureFile'])->name('read.getLiteratureFile');

    // Language
    Route::get('/locale/{locale}', [LocaleController::class, 'index'])->name('locale.change');
});
