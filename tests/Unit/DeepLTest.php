<?php

namespace Tests\Unit;

use App\Services\DeepL;
use DeepL\DeepLClient;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeepLTest extends TestCase
{
    #[Group('metin')]
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
}
