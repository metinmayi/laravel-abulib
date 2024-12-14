<?php

namespace App\Actions;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading litterature variant
 */
class UploadLitteratureVariantAction
{
  /**
   * Constructor
   * @param array<mixed> $data Data passed in.
   */
    public function __construct(protected array $data)
    {
    }

  /**
   * Main method
   */
    public function handle(int $litteratureId): bool
    {
        if (! Litterature::find($litteratureId)) {
            Log::error('Tried to upload a litterature variant without an existing litterature', ['litterature_id' => $litteratureId, 'data' => $this->data]);
            return false;
        }

        $alreadyExists = LitteratureVariant::where('litterature_id', $litteratureId)
            ->where('language', $this->data['language'])
            ->exists();
        if ($alreadyExists) {
            Log::error('Tried to upload a litterature variant with an existing language', ['litterature_id' => $litteratureId, 'data' => $this->data]);
            return false;
        }

        $fileName = Storage::disk('local')->putFile('', $this->data['file']);

        $this->data['url'] = $fileName;
        $this->data['litterature_id'] = $litteratureId;

        $variant = new LitteratureVariant($this->data);
        return $variant->save();
    }
}
