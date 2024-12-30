<?php

namespace App\Actions;

use App\Data\UploadLiteratureData;
use App\Models\Literature;
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
        protected UploadLiteratureVariantAction $uploadLiteratureVariantAction
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

            foreach (Literature::LANGUAGES as $language) {
                $this->uploadLiteratureVariantAction->handle($literature->id, $this->data->literatures[$language]);
            }
        });
    }
}
