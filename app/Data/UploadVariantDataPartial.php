<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadVariantDataPartial extends Data
{
  /**
   * Constructor.
   */
    public function __construct(
        public string $language,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $url = null,
        public ?int $literature_id = null,
        public ?UploadedFile $file = null
    ) {
    }
}
