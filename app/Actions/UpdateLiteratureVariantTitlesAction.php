<?php

namespace App\Actions;

use App\Models\LiteratureVariant;

class UpdateLiteratureVariantTitlesAction
{
    /**
     * Constructor.
     * @param array<mixed> $data Data passed in.
     */
    public function __construct(
        protected array $data
    ) {
    }

    /**
     * Main method
     */
    public function handle(int $literatureId): void
    {
        foreach ($this->data as $key => $value) {
            if (!str_ends_with($key, '-title')) {
                continue;
            }

            $language = explode('-', $key)[0];

            LiteratureVariant::query()->where('literature_id', $literatureId)
                ->where('language', $language)
                ->firstOrNew([
                    'literature_id' => $literatureId,
                    'language' => $language,
                ])->fill([
                    'title' => $value,
                ])
                ->save();
        }
    }
}
