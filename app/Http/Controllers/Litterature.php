<?php

namespace App\Http\Controllers;

use App\Actions\UploadLitteratureAction;
use App\Actions\UploadLitteratureVariantAction;
use App\Http\Requests\LitteratureUploadRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class Litterature extends Controller
{
    /**
     * Upload litterature
     */
    public function uploadLitterature(LitteratureUploadRequest $request): Response | ResponseFactory
    {
        $data = $request->all();
        $uploadLitteratureAction = new UploadLitteratureAction($data, new UploadLitteratureVariantAction($data));
        $uploadLitteratureAction->handle();
        return response(null, 201);
    }
}
