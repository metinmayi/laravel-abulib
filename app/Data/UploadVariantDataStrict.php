<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadVariantDataStrict extends Data
{
  /**
   * Constructor.
   */
    public function __construct(
        public string $language,
        public string $title,
        public ?string $description,
        public ?string $url,
        public ?int $literature_id,
        public ?UploadedFile $file
    ) {
    }
}
