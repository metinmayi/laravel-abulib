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
        $didDelete = Storage::delete($filePath);
        if (! $didDelete) {
            Log::error('Failed to delete file related to variant', ['id' => $id, 'filePath' => $filePath]);
            return false;
        }

        $litteratureVariants = count(LitteratureVariant::query()->where('litterature_id', '=', $variant->litterature_id)->get());
        if ($litteratureVariants === 1) {
            $litterature = Litterature::find($variant->litterature_id);
            if (! $litterature) {
                Log::error('Failed to delete literature when deleting last variant', ['litterature_id' => $variant->litterature_id, 'variantId' => $id, 'filePath' => $filePath]);
                return false;
            }
            return $litterature->delete();
        }

        return $variant->delete();
    }
}
