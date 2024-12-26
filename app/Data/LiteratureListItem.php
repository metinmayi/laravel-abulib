<?php

namespace App\Data;

class LiteratureListItem
{
    protected const REQUIRED_KEYS = [
        'id',
        'variantId',
        'category',
        'title',
        'description',
        'availableLanguages',
    ];

    public string $id;
    public ?string $variantId;
    public string $title;
    public string $description;
    public string $category;

    /** @var array<string> */
    public array $availableLanguages;

    /**
     * Constructor
     */
    public function __construct(protected \stdClass $literatureData)
    {
        $this->validate();
    }

    /**
     * Validate the literature item
     */
    private function validate(): bool
    {
        foreach (self::REQUIRED_KEYS as $key) {
            if (! property_exists($this->literatureData, $key)) {
                throw new \InvalidArgumentException('Missing required key: ' . $key);
            }

            $this->{$key} = $this->literatureData->{$key};
        }

        return true;
    }
}
