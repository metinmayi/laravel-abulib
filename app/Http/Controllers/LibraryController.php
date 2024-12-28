<?php

namespace App\Http\Controllers;

use App\Actions\GetLiteratureListAction;
use Illuminate\Contracts\View\View;

class LibraryController extends Controller
{
    /**
     * Display the library page.
     */
    public function index(): View
    {
        $literatureList = (new GetLiteratureListAction(app()->getLocale()))->handle();
        return view('library.index', ['literatureList' => $literatureList]);
    }
}
