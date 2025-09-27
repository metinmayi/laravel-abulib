<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadVariantDataLax extends Data
{
  /**
   * Constructor.
   */
    public function __construct(
        public string $language,
        public ?string $title,
        public ?string $description,
        public ?string $url,
        public ?int $literature_id,
        public ?UploadedFile $file
    ) {
    }

    public function toStrict(): UploadVariantDataStrict
    {
        if ($this->title === null) {
            throw new \InvalidArgumentException('Title cannot be null when converting to strict data.');
        }

        return new UploadVariantDataStrict($this->language, $this->title, $this->description, $this->url, $this->literature_id, $this->file);
    }
}
