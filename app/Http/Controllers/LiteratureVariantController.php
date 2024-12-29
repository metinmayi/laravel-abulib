<?php

namespace App\Http\Controllers;

use App\Actions\DeleteVariantAction;
use App\Actions\EditLiteratureVariantAction;
use App\Actions\UploadLiteratureVariantAction;
use App\Http\Requests\LiteratureVariantUpdateRequest;
use App\Http\Requests\LiteratureVariantUploadRequest;
use App\Models\LiteratureVariant as ModelsLiteratureVariant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class LiteratureVariantController extends Controller
{
    /**
     * Get literature binary. Used for PDF source.
     */
    public function getLiteratureBinary(int $id): ResponseFactory | Response
    {
        $variant = ModelsLiteratureVariant::query()->find($id);
        if (!$variant || !$variant->url) {
            return response(null, 404);
        }

        $content = Storage::get($variant->url);
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Upload a literature variant
     */
    public function upload(int $literatureId, LiteratureVariantUploadRequest $request): RedirectResponse
    {
        $action = new UploadLiteratureVariantAction($request->validated());

        [$success] = $action->handle($literatureId);
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect()->back(201);
    }

    /**
     * Edit a variant.
     */
    public function edit(int $id, LiteratureVariantUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $success = (new EditLiteratureVariantAction($id, $validated))->handle();
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }
        return redirect()->back();
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

        return redirect(route('library.index'));
    }
}
