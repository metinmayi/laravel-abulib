<?php

namespace Tests\Feature;

use App\Actions\UploadLiteratureAction;
use App\Actions\UploadVariantAction;
use App\Data\UploadLiteratureData;
use App\Data\UploadVariantDataLax;
use App\Data\UploadVariantDataStrict;
use App\Models\Literature;
use App\Models\Variant;
use App\Services\DeepL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('metin')]
class UploadLiteratureActionTest extends TestCase
{
    use RefreshDatabase;

    public function testTranslatesExactlyMissingTitles(): void
    {
        $submitted = 'Original Title';
        $variants = [
            'english' => new UploadVariantDataLax('english', 'Original Title', null, null, null, null),
            'kurdish' => new UploadVariantDataLax('kurdish', null, null, null, null, null),
            'arabic' => new UploadVariantDataLax('arabic', null, null, null, null, null),
            'swedish' => new UploadVariantDataLax('swedish', null, null, null, null, null),
        ];
    $data = new UploadLiteratureData('poem', $variants);

        $deepL = $this->mockDeepL([
            ['submitted' => $submitted, 'lang' => 'kurdish', 'returns' => 'Kurdish Title'],
            ['submitted' => $submitted, 'lang' => 'arabic', 'returns' => 'Arabic Title'],
            ['submitted' => $submitted, 'lang' => 'swedish', 'returns' => 'Swedish Title'],
        ]);
        $uploader = $this->mockUploadVariantActionExpectingSequence([
            ['lang' => 'english', 'title' => 'Original Title'],
            ['lang' => 'kurdish', 'title' => 'Kurdish Title'],
            ['lang' => 'arabic', 'title' => 'Arabic Title'],
            ['lang' => 'swedish', 'title' => 'Swedish Title'],
        ]);

        $this->runAction($data, $uploader, $deepL, $submitted);

        $this->assertEquals(1, Literature::count());
        $this->assertVariantTitles($variants, [
            'english' => 'Original Title',
            'kurdish' => 'Kurdish Title',
            'arabic' => 'Arabic Title',
            'swedish' => 'Swedish Title',
        ]);
    }

    public function testDoesNotTranslateWhenAllTitlesPresent(): void
    {
        $submitted = 'Irrelevant because all titles present';
        $variants = [
            'english' => new UploadVariantDataLax('english', 'Title EN', null, null, null, null),
            'kurdish' => new UploadVariantDataLax('kurdish', 'Title KU', null, null, null, null),
            'arabic' => new UploadVariantDataLax('arabic', 'Title AR', null, null, null, null),
            'swedish' => new UploadVariantDataLax('swedish', 'Title SV', null, null, null, null),
        ];
    $data = new UploadLiteratureData('poem', $variants);

        $deepL = $this->createMock(DeepL::class);
        $deepL->expects($this->never())->method('translate');

        $uploader = $this->mockUploadVariantActionExpectingSequence([
            ['lang' => 'english', 'title' => 'Title EN'],
            ['lang' => 'kurdish', 'title' => 'Title KU'],
            ['lang' => 'arabic', 'title' => 'Title AR'],
            ['lang' => 'swedish', 'title' => 'Title SV'],
        ]);

        $this->runAction($data, $uploader, $deepL, $submitted);
    }

    public function testTranslatesOnlySubsetOfMissingTitles(): void
    {
        $submitted = 'Original Title';
        $variants = [
            'english' => new UploadVariantDataLax('english', 'Original Title', null, null, null, null), // present
            'kurdish' => new UploadVariantDataLax('kurdish', 'Already Kurdish', null, null, null, null), // present
            'arabic' => new UploadVariantDataLax('arabic', null, null, null, null, null), // missing
            'swedish' => new UploadVariantDataLax('swedish', null, null, null, null, null), // missing
        ];
    $data = new UploadLiteratureData('poem', $variants);

        $deepL = $this->mockDeepL([
            ['submitted' => $submitted, 'lang' => 'arabic', 'returns' => 'Arabic Title'],
            ['submitted' => $submitted, 'lang' => 'swedish', 'returns' => 'Swedish Title'],
        ]);
        $uploader = $this->mockUploadVariantActionExpectingSequence([
            ['lang' => 'english', 'title' => 'Original Title'],
            ['lang' => 'kurdish', 'title' => 'Already Kurdish'],
            ['lang' => 'arabic', 'title' => 'Arabic Title'],
            ['lang' => 'swedish', 'title' => 'Swedish Title'],
        ]);

        $this->runAction($data, $uploader, $deepL, $submitted);
    }

    public function testDelegationOrderMatchesLanguageConstantOrder(): void
    {
        $submitted = 'Title';
        // Only english has a title; others will be translated
        $variants = [
            'english' => new UploadVariantDataLax('english', 'Title', null, null, null, null),
            'kurdish' => new UploadVariantDataLax('kurdish', null, null, null, null, null),
            'arabic' => new UploadVariantDataLax('arabic', null, null, null, null, null),
            'swedish' => new UploadVariantDataLax('swedish', null, null, null, null, null),
        ];
    $data = new UploadLiteratureData('poem', $variants);

        $deepL = $this->mockDeepL([
            ['submitted' => $submitted, 'lang' => 'kurdish', 'returns' => 'Kurdish Title'],
            ['submitted' => $submitted, 'lang' => 'arabic', 'returns' => 'Arabic Title'],
            ['submitted' => $submitted, 'lang' => 'swedish', 'returns' => 'Swedish Title'],
        ]);

        $observedOrder = [];
        $uploader = $this->createMock(UploadVariantAction::class);
        $uploader->expects($this->exactly(4))
            ->method('handle')
            ->willReturnCallback(function (int $literatureId, UploadVariantDataStrict $strict) use (&$observedOrder) {
                $observedOrder[] = $strict->language;
                return [true, 1];
            });

        $this->runAction($data, $uploader, $deepL, $submitted);
        $this->assertSame(Variant::LANGUAGES, $observedOrder, 'Variant processing order mismatch');
    }

    public function testDelegatesAllVariantsWithStrictData(): void
    {
        $submitted = 'Orig';
        $variants = [
            'english' => new UploadVariantDataLax('english', 'Orig', null, null, null, null),
            'kurdish' => new UploadVariantDataLax('kurdish', null, null, null, null, null),
            'arabic' => new UploadVariantDataLax('arabic', null, null, null, null, null),
            'swedish' => new UploadVariantDataLax('swedish', null, null, null, null, null),
        ];
    $data = new UploadLiteratureData('poem', $variants);
        $deepL = $this->mockDeepL([
            ['submitted' => $submitted, 'lang' => 'kurdish', 'returns' => 'Kurdish T'],
            ['submitted' => $submitted, 'lang' => 'arabic', 'returns' => 'Arabic T'],
            ['submitted' => $submitted, 'lang' => 'swedish', 'returns' => 'Swedish T'],
        ]);

        $uploader = $this->mockUploadVariantActionExpectingSequence([
            ['lang' => 'english', 'title' => 'Orig'],
            ['lang' => 'kurdish', 'title' => 'Kurdish T'],
            ['lang' => 'arabic', 'title' => 'Arabic T'],
            ['lang' => 'swedish', 'title' => 'Swedish T'],
        ]);

        $this->runAction($data, $uploader, $deepL, $submitted);
    }

    /* ========================= Helper Methods ========================= */

    /**
     * @param array<int, array{submitted:string, lang:string, returns:string}> $expectedCalls
     */
    private function mockDeepL(array $expectedCalls): DeepL
    {
        $deepL = $this->createMock(DeepL::class);
        $deepL->expects($this->exactly(count($expectedCalls)))
            ->method('translate')
            ->willReturnCallback(function (string $submitted, string $lang) use (&$expectedCalls) {
                $expected = array_shift($expectedCalls);
                $this->assertNotNull($expected, 'Too many translate calls');
                $this->assertSame($expected['submitted'], $submitted, 'Submitted title mismatch');
                $this->assertSame($expected['lang'], $lang, 'Translation language order mismatch');
                return $expected['returns'];
            });
        return $deepL;
    }

    /**
     * @param array<int, array{lang:string,title:string}> $sequence
     */
    private function mockUploadVariantActionExpectingSequence(array $sequence): UploadVariantAction
    {
        $uploader = $this->createMock(UploadVariantAction::class);
        $uploader->expects($this->exactly(count($sequence)))
            ->method('handle')
            ->willReturnCallback(function (int $literatureId, UploadVariantDataStrict $strict) use (&$sequence) {
                $this->assertTrue($literatureId > 0, 'Literature should be persisted');
                $expected = array_shift($sequence);
                $this->assertNotNull($expected, 'Too many uploader calls');
                $this->assertSame($expected['lang'], $strict->language, 'Unexpected uploader language');
                $this->assertSame($expected['title'], $strict->title, 'Unexpected uploader title');
                return [true, 1];
            });
        return $uploader;
    }

    private function runAction(UploadLiteratureData $data, UploadVariantAction $uploader, DeepL $deepL, string $submittedTitle): void
    {
        $action = new UploadLiteratureAction($data, $uploader, $submittedTitle, $deepL);
        $action->handle();
    }

    /**
     * @param array<string, UploadVariantDataLax> $variants
     * @param array<string, string> $expected
     */
    private function assertVariantTitles(array $variants, array $expected): void
    {
        foreach ($expected as $lang => $title) {
            $this->assertArrayHasKey($lang, $variants, "Missing variant for $lang");
            $this->assertSame($title, $variants[$lang]->title, "Title mismatch for $lang");
        }
    }
}
