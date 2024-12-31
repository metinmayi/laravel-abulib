<?php

namespace App\Actions;

use App\Data\UploadVariantData;
use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading literature variant
 */
class UploadVariantAction
{
  /**
   * Constructor.
   */
    public function __construct()
    {
    }

  /**
   * Main method
   * @return array{bool, int}
   */
    public function handle(int $literatureId, UploadVariantData $data): array
    {
        if (! Literature::find($literatureId)) {
            Log::error('Tried to upload a literature variant without an existing literature', ['literature_id' => $literatureId, 'data' => $data]);
            return [false, -1];
        }

        $alreadyExists = Variant::where('literature_id', $literatureId)
            ->where('language', $data->language)
            ->exists();
        if ($alreadyExists) {
            Log::error('Tried to upload a literature variant with an existing language', ['literature_id' => $literatureId, 'data' => $data]);
            return [false, -1];
        }

        if (isset($data->file)) {
            $fileName = Storage::putFile('', $data->file) ?: null;
            $data->url = $fileName;
        }

        $data->literature_id = $literatureId;

        $variant = new Variant((array) $data);
        return [$variant->save(), $variant->id];
    }
}
