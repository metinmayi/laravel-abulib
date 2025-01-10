<?php

namespace App\Http\Controllers;

use App\Actions\GetLiteratureListAction;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Request;

class LibraryController extends Controller
{
    /**
     * Display the library page.
     */
    public function index(Request $request, GetLiteratureListAction $action): View
    {
        if (is_string($request->get('languages'))) {
            $action->setRequiredLanguages(explode(',', $request->get('languages')));
        }

        return view('library.index', ['literatureList' => $action->handle()]);
    }
}
