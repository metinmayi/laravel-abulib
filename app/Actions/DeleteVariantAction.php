<?php

namespace App\Actions;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading litterature variant
 */
class DeleteVariantAction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
    * Main method
    */
    public function handle(int $id): ?bool
    {
        $variant = LitteratureVariant::find($id);
        if (! $variant) {
            Log::error('Tried to delete a litterature variant without it existing', ['id' => $id]);
            return false;
        }

        $filePath = $variant->url;
        if (! Storage::delete($filePath)) {
            Log::error('Failed to delete file related to variant', ['id' => $id, 'filePath' => $filePath]);
            return false;
        }

        $remainingVariants = LitteratureVariant::where('litterature_id', $variant->litterature_id)->count();
        if ($remainingVariants === 1) {
            $litterature = Litterature::findOrFail($variant->litterature_id);
            return $litterature->delete();
        }

        return $variant->delete();
    }
}
