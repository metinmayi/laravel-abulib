<?php

namespace App\Actions;

use App\Data\LiteratureListItem;
use Illuminate\Support\Facades\DB;

/**
 * List for retrieving list of literatures
 */
class GetLiteratureListAction
{
    protected string $language;
    /**
     * @var array<string>|null
     */
    protected ?array $requiredLanguages = null;

    /**
     * @var array<string>|null
     */
    protected ?array $categories = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setLanguage(app()->getLocale());
    }

    /**
     * Set language
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Set languages
     * @param array<string> $languages Languages.
     */
    public function setRequiredLanguages(array $languages): self
    {
        $this->requiredLanguages = $languages;
        return $this;
    }

    /**
     * Handle the action
     * @return array<LiteratureListItem>
     */
    public function handle(): array
    {
        $query = DB::table('literatures')
            ->leftJoin('variants as lv', function ($join) {
                $join->on('literatures.id', '=', 'lv.literature_id');
            })
            ->select(
                'literatures.id',
                'literatures.category',
                'lv.title',
                'lv.description',
                'lv.id as variantId',
                DB::raw("(SELECT GROUP_CONCAT(language) 
                  FROM variants 
                  WHERE variants.literature_id = literatures.id 
                  AND variants.url IS NOT NULL) as availableLanguages")
            );

        if ($this->requiredLanguages) {
            $query->whereIn('lv.language', $this->requiredLanguages);
            $query->where('lv.url', '!=', null);
            $query->groupBy('literatures.id');
        } else {
            $query->where('lv.language', $this->language);
        }

        $result = $query->get()
            ->map(function ($literature) {
                // Convert availableLanguages string to array
                $literature->availableLanguages = explode(',', $literature->availableLanguages);
                return $literature;
            });

        $literatureList = [];
        foreach ($result as $literature) {
            $literatureList[] = new LiteratureListItem($literature);
        }

        return $literatureList;
    }
}
