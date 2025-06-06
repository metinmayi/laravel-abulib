<?php
require_once __DIR__ . '/vendor/autoload.php';

$projectDir = __DIR__ . '/resources';
$sourceLang = 'english';
$targetLangs = [
    'swedish' => 'SV',
    'arabic' => 'AR',
];
$translationFunctions = ['__'];
$matches = [];

// Step 1: Extract translatable strings from code
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectDir));
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

    $contents = file_get_contents($file->getPathname());

    foreach ($translationFunctions as $fn) {
        $pattern = sprintf('/%s\s*\(\s*[\'"](.+?)[\'"]\s*[,\)]/', preg_quote($fn));
        preg_match_all($pattern, $contents, $found);

        foreach ($found[1] as $str) {
            $matches[$str] = $str;
        }
    }
}

// Step 2: Update english.json
$langPath = "$projectDir/lang";
$enFile = "$langPath/$sourceLang.json";
@mkdir($langPath, 0777, true);
$existingEn = file_exists($enFile) ? json_decode(file_get_contents($enFile), true) : [];
$updatedEn = $existingEn + $matches;

ksort($updatedEn);
file_put_contents($enFile, json_encode($updatedEn, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "✅ Updated $enFile with " . count($updatedEn) . " entries.\n";

// Step 3: Translate missing strings to each target language
$apiKey = getenv('DEEPL_API_KEY');
if (!$apiKey) {
    echo "❌ DEEPL_API_KEY not set in environment.\n";
    exit(1);
}

foreach ($targetLangs as $lang => $deeplCode) {
    $langFile = "$langPath/$lang.json";
    $existing = file_exists($langFile) ? json_decode(file_get_contents($langFile), true) : [];

    $missing = array_diff_key($updatedEn, $existing);
    if (empty($missing)) {
        echo "✅ No missing translations for $lang.\n";
        continue;
    }

    if (!$deeplCode) {
        echo "⚠️ Skipping DeepL for $lang (not supported). Please translate manually.\n";
        continue;
    }

    $translations = deeplTranslate(array_keys($missing), $deeplCode, $apiKey);

    $final = $existing + array_combine(array_keys($missing), $translations);
    ksort($final);
    file_put_contents($langFile, json_encode($final, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "🌍 Translated and updated $langFile (" . count($translations) . " new entries)\n";
}

// Translation function using DeepL
function deeplTranslate(array $texts, string $targetLang, string $apiKey): array {
    $deepL = new \DeepL\DeepLClient($apiKey);
    $results = $deepL->translateText($texts, 'EN', $targetLang);
    // The value of the result is an associated array. I only want the value to the the 'text' of that array.
    $results = array_map(function($result) {
        return $result->text;
    }, $results);
    return $results;
}