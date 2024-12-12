<?php

namespace App\Http\Controllers;

use App\Actions\UploadLitteratureVariantAction;
use App\Http\Requests\LitteratureVariantUploadRequest;
use App\Models\Litterature;
use App\Models\LitteratureVariant as ModelsLitteratureVariant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LitteratureVariant extends Controller
{
    /**
     * Get litterature binary. Used for PDF source.
     */
    public function getLitteratureBinary(int $id): BinaryFileResponse | ResponseFactory | Response
    {
        $variant = ModelsLitteratureVariant::find($id);
        if (!$variant) {
            return response(null, 404);
        }

        $pdf = Storage::disk('local')->path('smallPdf.pdf');
        return response()->file($pdf);
    }

    /**
     * Upload a litterature variant
     */
    public function uploadLitteratureVariant(LitteratureVariantUploadRequest $request): RedirectResponse
    {
        $action = new UploadLitteratureVariantAction($request->all());
        $success = $action->handle($request->get('litterature_id'));
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect()->back(201);
    }
}
