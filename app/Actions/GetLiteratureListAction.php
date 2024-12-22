<?php

namespace App\Actions;

use App\Models\LiteratureVariant;
use Illuminate\Support\Facades\DB;

/**
 * List for retrieving list of literatures
 */
class GetLiteratureListAction
{
    /**
     * Constructor
     */
    public function __construct(protected string $language)
    {
    }

    /**
     * Handle the action
     * @return array<mixed>
     */
    public function handle(): array
    {
        // Fetch language variants with available languages for each literature
        $languageVariants = DB::table('literatures')
          ->join('literature_variants', 'literatures.id', '=', 'literature_variants.literature_id')
          ->select(
              'literatures.id as literature_id',
              'literatures.category',
              'literature_variants.id',
              'literature_variants.title',
              'literature_variants.description',
              'literature_variants.language'
          )
          ->where('literature_variants.language', $this->language)
          ->get();

        $literatureIds = $languageVariants->pluck('literature_id')->unique();

        // Preload all available languages for the fetched literature IDs
        $languagesByLiterature = LiteratureVariant::whereIn('literature_id', $literatureIds)
          ->get()
          ->groupBy('literature_id')
          ->map(fn ($variants) => $variants->pluck('language')->toArray());

        // Add available languages to each variant
        $languageVariants->transform(function ($variant) use ($languagesByLiterature) {
            $variant->availableLanguages = $languagesByLiterature[$variant->literature_id] ?? [];
            return $variant;
        });

        return $languageVariants->toArray();
    }
}
