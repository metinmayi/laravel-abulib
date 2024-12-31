<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UploadLiteratureData extends Data
{
  /**
   * Constructor.
   * @param string                           $category    Category.
   * @param array<string, UploadVariantData> $literatures Array.
   */
    public function __construct(
        public string $category,
        public array $literatures
    ) {
    }
}
