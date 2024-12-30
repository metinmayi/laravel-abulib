<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UploadLiteratureData extends Data
{
  /**
   * Constructor.
   * @param string                                     $category    Category.
   * @param array<string, UploadLiteratureVariantData> $literatures Array.
   */
    public function __construct(
        public string $category,
        public array $literatures
    ) {
    }
}
