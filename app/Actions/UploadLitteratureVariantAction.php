<?php

namespace App\Actions;

use App\Models\LitteratureVariant;
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
    public function handle(int $litteratureId): void
    {
        $fileName = Storage::disk('litteratures')->putFile('', $this->data['file']);

        $this->data['url'] = $fileName;
        $this->data['litterature_id'] = $litteratureId;

        $variant = new LitteratureVariant($this->data);
        $variant->save();
    }
}
