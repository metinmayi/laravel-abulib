<?php

namespace App\Actions;

use App\Data\UploadLiteratureVariantData;
use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading literature variant
 */
class UploadLiteratureVariantAction
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
    public function handle(int $literatureId, UploadLiteratureVariantData $data): array
    {
        if (! Literature::find($literatureId)) {
            Log::error('Tried to upload a literature variant without an existing literature', ['literature_id' => $literatureId, 'data' => $data]);
            return [false, -1];
        }

        $alreadyExists = LiteratureVariant::where('literature_id', $literatureId)
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

        $variant = new LiteratureVariant((array) $data);
        return [$variant->save(), $variant->id];
    }
}
