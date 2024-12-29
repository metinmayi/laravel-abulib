<?php

namespace App\Http\Controllers;

use App\Actions\DeleteLiteratureAction;
use App\Actions\UpdateLiteratureVariantTitlesAction;
use App\Actions\UploadLiteratureAction;
use App\Actions\UploadLiteratureVariantAction;
use App\Http\Requests\LiteratureUploadRequest;
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
    public function store(LiteratureUploadRequest $request): RedirectResponse
    {
        $data = $request->all();
        $uploadLiteratureAction = new UploadLiteratureAction(
            $data,
            new UploadLiteratureVariantAction($data),
            new UpdateLiteratureVariantTitlesAction($data)
        );
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
