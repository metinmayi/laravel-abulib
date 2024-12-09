<?php

use App\Http\Controllers\Litterature;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test');
});

Route::post('/litterature', [Litterature::class, 'index']);