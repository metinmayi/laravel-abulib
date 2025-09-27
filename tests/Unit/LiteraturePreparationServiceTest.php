<?php

namespace Tests\Unit;

use App\Data\UploadLiteratureData;
use App\Data\UploadVariantDataPartial;
use App\Services\DeepL;
use App\Services\LiteraturePreparationService;
use Tests\TestCase;

class LiteraturePreparationServiceTest extends TestCase
{
    public function testThrowsWhenNoTitleProvided(): void
    {
        $data = new UploadLiteratureData('poem', [
            'english' => new UploadVariantDataPartial('english', null, null, null, null, null),
            'kurdish' => new UploadVariantDataPartial('kurdish', null, null, null, null, null),
            'arabic' => new UploadVariantDataPartial('arabic', null, null, null, null, null),
            'swedish' => new UploadVariantDataPartial('swedish', null, null, null, null, null),
        ]);
        $service = new LiteraturePreparationService($this->createMock(DeepL::class));
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one literature variant must have a title.');
        $service->prepare($data);
    }

    public function testFillsMissingTitles(): void
    {
        $data = new UploadLiteratureData('poem', [
            'english' => new UploadVariantDataPartial('english', 'Original'),
            'kurdish' => new UploadVariantDataPartial('kurdish'),
            'arabic' => new UploadVariantDataPartial('arabic'),
            'swedish' => new UploadVariantDataPartial('swedish'),
        ]);
    
        $deepL = $this->createMock(DeepL::class);
        $deepL->expects($this->exactly(3))
            ->method('translate')
            ->willReturnMap([
                ['Original', 'kurdish', 'Kurdish T'],
                ['Original', 'arabic', 'Arabic T'],
                ['Original', 'swedish', 'Swedish T'],
            ]);

        $service = new LiteraturePreparationService($deepL);
        $prepared = $service->prepare($data);
        $this->assertCount(4, $prepared);
        $this->assertSame('Original', $prepared['english']->title);
        $this->assertSame('Kurdish T', $prepared['kurdish']->title);
        $this->assertSame('Arabic T', $prepared['arabic']->title);
        $this->assertSame('Swedish T', $prepared['swedish']->title);
    }

    public function testNoTranslationWhenAllTitlesPresent(): void
    {
        $data = new UploadLiteratureData('poem', [
            'english' => new UploadVariantDataPartial('english', 'E', null, null, null, null),
            'kurdish' => new UploadVariantDataPartial('kurdish', 'K', null, null, null, null),
            'arabic' => new UploadVariantDataPartial('arabic', 'A', null, null, null, null),
            'swedish' => new UploadVariantDataPartial('swedish', 'S', null, null, null, null),
        ]);
        $deepL = $this->createMock(DeepL::class);
        $deepL->expects($this->never())->method('translate');
        $service = new LiteraturePreparationService($deepL);
        $prepared = $service->prepare($data);
        $this->assertSame('K', $prepared['kurdish']->title);
    }

    public function testSubsetMissingTitles(): void
    {
        $data = new UploadLiteratureData('poem', [
            'english' => new UploadVariantDataPartial('english', 'Original', null, null, null, null),
            'kurdish' => new UploadVariantDataPartial('kurdish', 'Has K', null, null, null, null),
            'arabic' => new UploadVariantDataPartial('arabic', null, null, null, null, null),
            'swedish' => new UploadVariantDataPartial('swedish', null, null, null, null, null),
        ]);
        $deepL = $this->createMock(DeepL::class);
        $deepL->expects($this->exactly(2))
            ->method('translate')
            ->willReturnMap([
                ['Original', 'arabic', 'Arabic T'],
                ['Original', 'swedish', 'Swedish T'],
            ]);
        $service = new LiteraturePreparationService($deepL);
        $prepared = $service->prepare($data);
        $this->assertSame('Has K', $prepared['kurdish']->title);
        $this->assertSame('Arabic T', $prepared['arabic']->title);
        $this->assertSame('Swedish T', $prepared['swedish']->title);
    }

    public function testThrowsIfLanguageMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing variant for language: arabic');
        $data = new UploadLiteratureData('poem', [
            'english' => new UploadVariantDataPartial('english', 'Original', null, null, null, null),
            'kurdish' => new UploadVariantDataPartial('kurdish', null, null, null, null, null),
            // 'arabic' missing
            'swedish' => new UploadVariantDataPartial('swedish', null, null, null, null, null),
        ]);
        $deepL = $this->createMock(DeepL::class);
        $service = new LiteraturePreparationService($deepL);
        $service->prepare($data);
    }
}
