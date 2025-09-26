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
            ->with('Hej världen', 'SV', 'EN')
            ->willReturn($mockResponse);

        $service = new DeepL($client);
        $service->translate('Hej världen', 'SV', 'EN');
    }

    public function testStrictFactoryThrowsWhenApiKeyMissing(): void
    {
        config()->set('services.deepl.key', null);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('DeepL API key is not configured.');
        
        new DeepL();
    }
}
