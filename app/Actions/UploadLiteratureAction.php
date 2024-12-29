<?php

namespace App\Actions;

use App\Models\Literature;
use Illuminate\Support\Facades\DB;

/**
 * Action for uploading Literature
 */
class UploadLiteratureAction
{
  /**
   * Constructor.
   * @param array<mixed> $data Data passed in.
   */
    public function __construct(
        protected array $data,
        protected UploadLiteratureVariantAction $uploadLiteratureVariantAction,
        protected UpdateLiteratureVariantTitlesAction $updateLiteratureVariantAction
    ) {
    }

  /**
   * Main method
   */
    public function handle(): void
    {
        DB::transaction(function () {
            $literature = new Literature(['category' => $this->data['category']]);
            $literature->save();

            $this->uploadLiteratureVariantAction->handle($literature->id);
            $this->updateLiteratureVariantAction->handle($literature->id);
        });
    }
}
