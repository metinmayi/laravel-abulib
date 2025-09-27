<?php

namespace App\Services;

use DeepL\DeepLClient;
use DeepL\DeepLException;

class DeepL
{
    protected DeepLClient $client;

    protected static $map = [
        'arabic' => 'AR',
        'english' => 'EN-GB',
        'kurdish' => 'KU',
        'swedish' => 'SV',
    ];

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
    public function translate(string $text, string $targetLang): string
    {
        $targetLang = self::$map[strtolower($targetLang)];
        if (!$targetLang) {
            throw new \InvalidArgumentException('Unsupported target language for DeepL translation.');
        }

        if ($targetLang === 'KU') {
            // DeepL does not support Kurdish translation
            return $text;
        }

        $result = $this->client->translateText($text, null, $targetLang);
        return $result->text;
    }

}
