<?php

namespace App\Actions;

use App\Data\UploadVariantData;
use App\Models\Literature;
use Illuminate\Support\Facades\DB;

/**
 * Action for uploading Literature
 */
class UploadLiteratureAction
{
  /**
   * Constructor.
   * @param array<UploadVariantData> $preparedVariants Prepared and validated variants.
   */
    public function __construct(
        protected string $category,
        protected array $preparedVariants,
        protected UploadVariantAction $uploadVariantAction,
    ) {
    }

  /**
   * Main method
   */
    public function handle(): void
    {
        DB::transaction(function () {
            $literature = new Literature(['category' => $this->category]);
            $literature->save();

            foreach ($this->preparedVariants as $strict) {
                $this->uploadVariantAction->handle($literature->id, $strict);
            }
        });
    }
}
