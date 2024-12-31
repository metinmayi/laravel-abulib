<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadVariantData extends Data
{
  /**
   * Constructor.
   */
    public function __construct(
        public string $title,
        public string $language,
        public ?string $description,
        public ?string $url,
        public ?int $literature_id,
        public ?UploadedFile $file
    ) {
    }
}
