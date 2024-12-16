<?php

namespace App\Http\Controllers;

use App\Actions\GetLitteratureListAction;
use Illuminate\Contracts\View\View;

class Library extends Controller
{
    /**
     * Display the library page.
     */
    public function index(): View
    {
        $litteratureList = (new GetLitteratureListAction('kurdish'))->handle();
        return view('library', ['litteratureList' => $litteratureList]);
    }
}
