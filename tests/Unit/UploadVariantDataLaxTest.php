<?php

namespace Tests\Unit;

use App\Data\UploadVariantDataLax;
use App\Data\UploadVariantDataStrict;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

class UploadVariantDataLaxTest extends TestCase
{
    public function testToStrictThrowsWhenTitleMissing(): void
    {
        $data = new UploadVariantDataLax(
            language: 'en',
            title: null,
            description: 'desc',
            url: 'file.pdf',
            literature_id: 123,
            file: null,
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title cannot be null when converting to strict data.');

        $data->toStrict();
    }

    public function testToStrictReturnsStrictDataWhenTitlePresent(): void
    {
        $data = new UploadVariantDataLax(
            language: 'en',
            title: 'My Title',
            description: 'desc',
            url: 'file.pdf',
            literature_id: 456,
            file: null,
        );

        $strict = $data->toStrict();

        $this->assertInstanceOf(UploadVariantDataStrict::class, $strict);
        $this->assertSame('en', $strict->language);
        $this->assertSame('My Title', $strict->title);
        $this->assertSame('desc', $strict->description);
        $this->assertSame('file.pdf', $strict->url);
        $this->assertSame(456, $strict->literature_id);
        $this->assertNull($strict->file);
    }
}
