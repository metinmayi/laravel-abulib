<?php

namespace App\Http\Controllers;

use App\Actions\DeleteLiteratureAction;
use App\Actions\UploadLiteratureAction;
use App\Actions\UploadLiteratureVariantAction;
use App\Http\Requests\LiteratureUploadRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LiteratureController extends Controller
{
    /**
     * Upload literature
     */
    public function upload(LiteratureUploadRequest $request): Response | ResponseFactory
    {
        $data = $request->all();
        $uploadLiteratureAction = new UploadLiteratureAction($data, new UploadLiteratureVariantAction($data));
        $uploadLiteratureAction->handle();
        return response(null, 201);
    }

    /**
     * Delete literature
     */
    public function delete(int $id): RedirectResponse
    {
        $action = new DeleteLiteratureAction($id);
        if (!$action->handle()) {
            return redirect(route('library.index'))->with('Error', 'An error occured. Please contact your son.');
        }
        return redirect(route('library.index'));
    }
}
