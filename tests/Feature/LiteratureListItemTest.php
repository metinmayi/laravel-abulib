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
            (object) [
                'title' => 'Title',
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
            ]],
            [(object) [
                'id' => 1,
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
            ]],
            [(object) [
                'id' => 1,
                'title' => 'Title',
                'availableLanguages' => ['kurdish', 'swedish'],
                'category' => 'category',
            ]],
            [(object) [
                'id' => 1,
                'title' => 'Title',
                'description' => 'Description',
                'category' => 'category',
            ]],
            [(object) [
                'id' => 1,
                'title' => 'Title',
                'description' => 'Description',
                'availableLanguages' => ['kurdish', 'swedish'],
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
        ];

        $listItem = new LiteratureListItem($data);
        $this->assertEquals($data->id, $listItem->id);
        $this->assertEquals($data->title, $listItem->title);
        $this->assertEquals($data->description, $listItem->description);
        $this->assertEquals($data->availableLanguages, $listItem->availableLanguages);
        $this->assertEquals($data->category, $listItem->category);
    }
}
