<?php

namespace App\Actions;

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
        $variant = LitteratureVariant::query()->where('id', '=', $id)->first();
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

        return $variant->delete();
    }
}
