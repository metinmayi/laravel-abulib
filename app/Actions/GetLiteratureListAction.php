<?php

namespace App\Actions;

use App\Data\LiteratureListItem;
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
     * @return array<LiteratureListItem>
     */
    public function handle(): array
    {
        $literatures = DB::table('literatures')
            ->leftJoin('literature_variants as lv', function ($join) {
                $join->on('literatures.id', '=', 'lv.literature_id')
                ->where('lv.language', '=', $this->language);
            })
            ->select(
                'literatures.id',
                'literatures.category',
                'lv.title',
                'lv.description',
                'lv.id as variantId',
                DB::raw("(SELECT GROUP_CONCAT(language) 
                  FROM literature_variants 
                  WHERE literature_variants.literature_id = literatures.id 
                  AND literature_variants.url IS NOT NULL) as availableLanguages")
            )
            ->get()
            ->map(function ($literature) {
            // Convert availableLanguages string to array
                $literature->availableLanguages = explode(',', $literature->availableLanguages);
                return $literature;
            });

        $literatureList = [];
        foreach ($literatures as $literature) {
            $literatureList[] = new LiteratureListItem($literature);
        }

        return $literatureList;
    }
}
