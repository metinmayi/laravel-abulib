<?php

namespace Tests\Feature;

use App\Data\LiteratureListItem;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LiteratureListItemTest extends TestCase
{
    /**
     * Test validation for literature list item.
     */
    #[DataProvider('literatureListItemDataProvider')]
    public function test_literature_list_item_throws_error_when_validation_fails(\stdClass $data): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new LiteratureListItem($data);
    }

    /**
     * Data provider for literature list item.
     * @return array<array<\stdClass>>
     */
    public static function literatureListItemDataProvider(): array
    {
        return [[
            (object) [ // Missing ID
                'title' => 'Title',
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
                'variantId' => 1,
            ]],
            [(object) [ // Missing title
                'id' => 1,
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
                'variantId' => 1,
            ]],
            [(object) [ // Missing description
                'id' => 1,
                'title' => 'Title',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
            ]],
            [(object) [ // Missing availableLanguages
                'id' => 1,
                'title' => 'Title',
                'description' => 'Description',
                'category' => 'category',
                'variantId' => 1,
            ]],
            [(object) [ // Missing category
                'id' => 1,
                'title' => 'Title',
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'variantId' => 1,
            ]],
            [(object) [ // Missing variantId
                'id' => 1,
                'title' => 'Title',
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
            ]],
        ];
    }

    /**
     * Test literature list item is created.
     */
    public function test_literature_list_item_is_created(): void
    {
        $data = (object) [
            'id' => 1,
            'title' => 'Title',
            'description' => 'Description',
            'availableLanguages' => ['kurdish', 'swedish'],
            'category' => 'category',
            'variantId' => 1
        ];

        $listItem = new LiteratureListItem($data);
        $this->assertEquals($data->id, $listItem->id);
        $this->assertEquals($data->title, $listItem->title);
        $this->assertEquals($data->description, $listItem->description);
        $this->assertEquals($data->availableLanguages, $listItem->availableLanguages);
        $this->assertEquals($data->category, $listItem->category);
    }
}
