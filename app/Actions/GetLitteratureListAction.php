<?php


namespace App\Actions;

use App\Models\LitteratureVariant;
use Illuminate\Support\Facades\DB;

/**
 * List for retrieving list of litteratures
 */
class GetLitteratureListAction
{

  public function __construct(protected string $language) {}

  public function handle(): array
  {
      // Fetch language variants with available languages for each litterature
      $languageVariants = DB::table('litteratures')
          ->join('litterature_variants', 'litteratures.id', '=', 'litterature_variants.litterature_id')
          ->select(
              'litteratures.id as litterature_id',
              'litteratures.category',
              'litterature_variants.id',
              'litterature_variants.title',
              'litterature_variants.description',
              'litterature_variants.language'
          )
          ->where('litterature_variants.language', $this->language)
          ->get();
  
      $litteratureIds = $languageVariants->pluck('litterature_id')->unique();
  
      // Preload all available languages for the fetched litterature IDs
      $languagesByLitterature = LitteratureVariant::whereIn('litterature_id', $litteratureIds)
          ->get()
          ->groupBy('litterature_id')
          ->map(fn ($variants) => $variants->pluck('language')->toArray());
  
      // Add available languages to each variant
      $languageVariants->transform(function ($variant) use ($languagesByLitterature) {
          $variant->availableLanguages = $languagesByLitterature[$variant->litterature_id] ?? [];
          return $variant;
      });
  
      return $languageVariants->toArray();
  }
  
}
