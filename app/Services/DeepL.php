<?php

namespace App\Services;

use DeepL\DeepLClient;
use DeepL\DeepLException;

class DeepL
{
    protected DeepLClient $client;

    public function __construct()
    {
        $this->client = new DeepLClient(env('DEEPL_API_KEY'));
    }

    /**
     * Translate a single text string.
     * @throws DeepLException
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $result = $this->client->translateText($text, $sourceLang, $targetLang);
        return is_array($result) ? $result[0]->text : $result->text;
    }

}
