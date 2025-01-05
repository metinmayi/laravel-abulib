<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Change the locale of the users session
     */
    public function index(string $locale): RedirectResponse
    {
        session(['locale' => $locale]);
        return redirect()->back();
    }
}
