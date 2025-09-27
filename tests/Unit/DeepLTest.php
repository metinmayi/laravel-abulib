<?php

namespace Tests\Unit;

use App\Services\DeepL;
use DeepL\DeepLClient;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeepLTest extends TestCase
{
    public function testTranslateIsCalled(): void
    {
        $mockResponse = (object)['text' => 'Hello world'];

        $client = $this->createMock(DeepLClient::class);
        $client->expects($this->once())
            ->method('translateText')
            ->with('Hej världen', null, 'EN-GB')
            ->willReturn($mockResponse);

        $service = new DeepL($client);
        $service->translate('Hej världen', 'english');
    }

    public function testStrictFactoryThrowsWhenApiKeyMissing(): void
    {
        config()->set('services.deepl.key', null);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('DeepL API key is not configured.');
        
        new DeepL();
    }

    public function testThrowErrorIfLanguageNotSupported(): void
    {
        $client = $this->createMock(DeepLClient::class);
        $service = new DeepL($client);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported target language for DeepL translation.');
        $service->translate('Some text', 'french');
    }

    public function testKurdishReturnsOriginalText(): void
    {
        $client = $this->createMock(DeepLClient::class);
        $client->expects($this->never())
            ->method('translateText');

        $service = new DeepL($client);
        $result = $service->translate('Some text', 'kurdish');
        $this->assertSame('Some text', $result);
    }
}
