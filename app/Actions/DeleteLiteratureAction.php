<?php

namespace App\Actions;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Support\Facades\Log;

/**
 * Action for uploading literature variant
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
        $literature = Literature::query()->find($this->id);
        if (! $literature) {
            Log::error('Tried to delete a literature without it existing', ['id' => $this->id]);
            return false;
        }

        $variants = Variant::query()->where('literature_id', $this->id)->get();
        foreach ($variants as $variant) {
            $action = new DeleteVariantAction();
            $action->handle($variant->id);
        }

        return (bool) $literature->delete();
    }
}
