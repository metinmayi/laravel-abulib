<?php

namespace App\Actions;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading literature variant
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
        $variant = Variant::find($id);
        if (! $variant) {
            Log::error('Tried to delete a literature variant without it existing', ['id' => $id]);
            return false;
        }

        $filePath = $variant->url;
        if ($filePath && ! Storage::delete($filePath)) {
            Log::error('Failed to delete file related to variant', ['id' => $id, 'filePath' => $filePath]);
            return false;
        }

        $remainingVariants = Variant::where('literature_id', $variant->literature_id)->count();
        if ($remainingVariants === 1) {
            $literature = Literature::findOrFail($variant->literature_id);
            return $literature->delete();
        }

        return $variant->delete();
    }
}
