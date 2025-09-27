<?php

namespace App\Actions;

use App\Data\UploadLiteratureData;
use App\Models\Literature;
use App\Models\Variant;
use App\Services\DeepL;
use Illuminate\Support\Facades\DB;

/**
 * Action for uploading Literature
 */
class UploadLiteratureAction
{
  /**
   * Constructor.
   */
    public function __construct(
        protected UploadLiteratureData $data,
        protected UploadVariantAction $uploadVariantAction,
        protected string $submittedTitle,
        protected DeepL $deepL = new DeepL()
    ) {
    }

  /**
   * Main method
   */
    public function handle(): void
    {
        DB::transaction(function () {
            $literature = new Literature(['category' => $this->data->category]);
            $literature->save();

            foreach (Variant::LANGUAGES as $language) {
                $variant = $this->data->variants[$language];
                if (!$variant->title) {
                    $variant->title = $this->deepL->translate($this->submittedTitle, $language);
                }
                $this->uploadVariantAction->handle($literature->id, $variant->toStrict());
            }
        });
    }
}
