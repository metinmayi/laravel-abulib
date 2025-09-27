<?php

namespace Tests\Feature;

use App\Actions\UploadLiteratureAction;
use App\Actions\UploadVariantAction;
use App\Data\UploadVariantData;
use App\Models\Literature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UploadLiteratureActionTest extends TestCase
{
    use RefreshDatabase;

    public function testDelegatesPreparedStrictVariants(): void
    {
        $strictVariants = [
            'english' => new UploadVariantData('english', 'Title EN', null, null, null, null),
            'kurdish' => new UploadVariantData('kurdish', 'Title KU', null, null, null, null),
            'arabic' => new UploadVariantData('arabic', 'Title AR', null, null, null, null),
            'swedish' => new UploadVariantData('swedish', 'Title SV', null, null, null, null),
        ];

        $uploader = $this->createMock(UploadVariantAction::class);
        $observed = [];
        $uploader->expects($this->exactly(4))
            ->method('handle')
            ->willReturnCallback(function (int $literatureId, UploadVariantData $strict) use (&$observed) {
                $this->assertTrue($literatureId > 0);
                $observed[$strict->language] = $strict->title;
                return [true, 1];
            });

        $action = new UploadLiteratureAction('poem', $strictVariants, $uploader);
        $action->handle();

        $this->assertEquals(1, Literature::count());
        $this->assertSame([
            'english' => 'Title EN',
            'kurdish' => 'Title KU',
            'arabic' => 'Title AR',
            'swedish' => 'Title SV',
        ], $observed);
    }

    /* Helpers removed after refactor */
}
