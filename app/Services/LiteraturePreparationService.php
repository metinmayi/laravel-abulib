<?php

namespace App\Services;

use App\Data\UploadLiteratureData;
use App\Data\UploadVariantData;
use App\Models\Variant;

class LiteraturePreparationService
{
    public function __construct(private DeepL $deepL = new DeepL())
    {
    }

    /**
     * Prepare strict variant data: validates at least one title, fills missing titles via DeepL and returns array of UploadVariantDataStrict indexed by language.
     * @return array<string, UploadVariantData>
     */
    public function prepare(UploadLiteratureData $data): array
    {
        // Find first non-empty title
        $title = null;
        foreach ($data->variants as $language => $variant) {
            if (! empty($variant->title)) {
                $title = $variant->title;
                break;
            }
        }

        if (!$title) {
            throw new \InvalidArgumentException('At least one literature variant must have a title.');
        }

        $strict = [];
        foreach (Variant::LANGUAGES as $language) {
            if (!isset($data->variants[$language])) {
                throw new \InvalidArgumentException("Missing variant for language: $language");
            }

            $variant = $data->variants[$language];
            if (!$variant->title) {
                $variant->title = $this->deepL->translate($title, $language);
            }
            $strict[$language] = new UploadVariantData($variant->language, $variant->title, $variant->description, $variant->url, $variant->literature_id, $variant->file);
        }
        return $strict;
    }
}
