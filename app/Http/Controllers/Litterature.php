<?php

namespace App\Http\Controllers;

use App\Actions\DeleteLiteratureAction;
use App\Actions\UploadLitteratureAction;
use App\Actions\UploadLitteratureVariantAction;
use App\Http\Requests\LitteratureUploadRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class Litterature extends Controller
{
    /**
     * Upload litterature
     */
    public function upload(LitteratureUploadRequest $request): Response | ResponseFactory
    {
        $data = $request->all();
        $uploadLitteratureAction = new UploadLitteratureAction($data, new UploadLitteratureVariantAction($data));
        $uploadLitteratureAction->handle();
        return response(null, 201);
    }

    /**
     * Delete litterature
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
