<?php

namespace App\Http\Controllers;

use App\Http\Requests\LitteratureUploadRequest;
use App\Models\Litterature as ModelsLitterature;
use App\Models\LitteratureVariant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class Litterature extends Controller
{
    public function uploadLitterature(LitteratureUploadRequest $request): Response | ResponseFactory
    {
        // Transaction all of this
        $litterature = new ModelsLitterature(['category' => $request->input('category')]);
        $litterature->save();

        $data = $request->only('title', 'description', 'language');
        $data['litterature_id'] = $litterature->id;

        // Move to uploader
        $fileName = "{$data['title']}-{$data['language']}.pdf";
        Storage::disk('litteratures')->putFile("{$fileName}", $request->file('file'));
        $data['url'] = $fileName;

        // Store file
        $variant = new LitteratureVariant($data);
        $variant -> save();
        return response(null, 201);
    }
}
