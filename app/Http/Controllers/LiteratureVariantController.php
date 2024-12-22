<?php

namespace App\Http\Controllers;

use App\Actions\DeleteVariantAction;
use App\Actions\UploadLiteratureVariantAction;
use App\Http\Requests\LiteratureVariantUploadRequest;
use App\Models\LiteratureVariant as ModelsLiteratureVariant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LiteratureVariantController extends Controller
{
    /**
     * Get literature binary. Used for PDF source.
     */
    public function getLiteratureBinary(int $id): BinaryFileResponse | ResponseFactory | Response
    {
        $variant = ModelsLiteratureVariant::find($id);
        if (!$variant) {
            return response(null, 404);
        }

        $pdf = Storage::disk('local')->path($variant->url);
        return response()->file($pdf);
    }

    /**
     * Upload a literature variant
     */
    public function uploadLiteratureVariant(LiteratureVariantUploadRequest $request): RedirectResponse
    {
        $action = new UploadLiteratureVariantAction($request->safe()->all());

        /** @var int */
        $literatureId = $request->get('literature_id');
        [$success] = $action->handle($literatureId);
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect()->back(201);
    }

    /**
     * Delete a variant.
     */
    public function delete(int $id): RedirectResponse
    {
        $action = new DeleteVariantAction();
        $success = $action->handle($id);
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect()->back(201);
    }
}
