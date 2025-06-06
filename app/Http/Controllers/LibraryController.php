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


        if (is_string($request->get('categories'))) {
            $action->setRequiredCategories(explode(',', $request->get('categories')));
        }


        return view('library.index', ['literatureList' => $action->handle()]);
    }

    public function paligo(): View
    {
        return view('paligo.index');
    }
}
