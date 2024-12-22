<?php

namespace App\Actions;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading litterature variant
 */
class DeleteLiteratureAction
{
    /**
     * Constructor.
     */
    public function __construct(protected int $id)
    {
    }

    /**
    * Main method
    */
    public function handle(): bool
    {
        $literature = Litterature::query()->find($this->id);
        if (! $literature) {
            Log::error('Tried to delete a litterature without it existing', ['id' => $this->id]);
            return false;
        }

        $variants = LitteratureVariant::query()->where('litterature_id', $this->id)->get();
        foreach ($variants as $variant) {
            $action = new DeleteVariantAction();
            $action->handle($variant->id);
        }

        return (bool) $literature->delete();
    }
}
