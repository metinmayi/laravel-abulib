<?php

namespace App\Http\Controllers;

use App\Actions\DeleteVariantAction;
use App\Actions\EditVariantAction;
use App\Actions\UploadVariantAction;
use App\Data\UploadVariantData;
use App\Http\Requests\VariantUpdateRequest;
use App\Models\Variant as ModelsVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VariantController extends Controller
{
    /**
     * Upload a literature variant
     */
    public function store(UploadVariantData $data): RedirectResponse
    {
        $action = new UploadVariantAction();

        [$success] = $action->handle($data->literature_id ?? -1, $data);
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect()->back(201);
    }

    /**
     * Show the form for editing a variant.
     */
    public function edit(int $variant): View
    {
        return view('variant.edit', ['variant' => ModelsVariant::findOrFail($variant)]);
    }

    /**
     * Edit a variant.
     */
    public function update(int $variant, VariantUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $success = (new EditVariantAction($variant, $validated))->handle();
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }
        return redirect()->back();
    }

    /**
     * Delete a variant.
     */
    public function destroy(int $variant): RedirectResponse
    {
        $action = new DeleteVariantAction();
        $success = $action->handle($variant);
        if (!$success) {
            return redirect()->back()->with('Error', 'Something went wrong. Contact your son.');
        }

        return redirect(route('library.index'));
    }
}
