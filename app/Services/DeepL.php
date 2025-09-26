<?php

namespace App\Services;

use DeepL\DeepLClient;
use DeepL\DeepLException;

class DeepL
{
    protected DeepLClient $client;

    public function __construct(?DeepLClient $client = null)
    {
        $key = config('services.deepl.key');
        if (is_string($key) === false || empty($key)) {
            throw new \InvalidArgumentException('DeepL API key is not configured.');
        }

        $this->client = $client ?? new DeepLClient($key);
    }

    /**
     * Translate a single text string.
     * @throws DeepLException
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $result = $this->client->translateText($text, $sourceLang, $targetLang);
        return $result->text;
    }

}
