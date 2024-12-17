<?php

namespace App\Actions;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading litterature variant
 */
class UploadLitteratureVariantAction
{
    protected int $uploadedId;

  /**
   * Constructor
   * @param array<mixed> $data Data passed in.
   */
    public function __construct(protected array $data)
    {
    }

  /**
   * Main method
   * @return array{bool, int}
   */
    public function handle(int $litteratureId): array
    {
        if (! Litterature::find($litteratureId)) {
            Log::error('Tried to upload a litterature variant without an existing litterature', ['litterature_id' => $litteratureId, 'data' => $this->data]);
            return [false, -1];
        }

        $alreadyExists = LitteratureVariant::where('litterature_id', $litteratureId)
            ->where('language', $this->data['language'])
            ->exists();
        if ($alreadyExists) {
            Log::error('Tried to upload a litterature variant with an existing language', ['litterature_id' => $litteratureId, 'data' => $this->data]);
            return [false, -1];
        }

        /** @var UploadedFile */
        $file = $this->data['file'];
        $fileName = Storage::disk('local')->putFile('', $file);

        $this->data['url'] = $fileName;
        $this->data['litterature_id'] = $litteratureId;

        $variant = new LitteratureVariant($this->data);
        return [$variant->save(), $variant->id];
    }
}
