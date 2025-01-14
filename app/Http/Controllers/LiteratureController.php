<?php

namespace App\Http\Controllers;

use App\Actions\DeleteLiteratureAction;
use App\Actions\UploadLiteratureAction;
use App\Actions\UploadVariantAction;
use App\Data\UploadLiteratureData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LiteratureController extends Controller
{
    /**
     * Show form to create literature.
     */
    public function create(): View
    {
        return view('literature.create-literature');
    }

    /**
     * Upload literature
     */
    public function store(UploadLiteratureData $data): RedirectResponse
    {
        $uploadLiteratureAction = new UploadLiteratureAction($data, new UploadVariantAction());
        $uploadLiteratureAction->handle();
        return redirect()->route('library.index');
    }

    /**
     * Delete literature
     */
    public function destroy(int $literature): RedirectResponse
    {
        $action = new DeleteLiteratureAction($literature);
        if (!$action->handle()) {
            return redirect(route('library.index'))->with('Error', 'An error occured. Please contact your son.');
        }
        return redirect(route('library.index'));
    }
}
