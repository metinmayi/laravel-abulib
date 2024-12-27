<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Contracts\View\View;

class ReadController extends Controller
{
    /**
     * Display the reader page.
     */
    public function index(int $variantId): View
    {
        $variant = LiteratureVariant::query()->findOrFail($variantId)->toArray();
        $literature = Literature::query()->findOrFail($variant['literature_id'])->toArray();
        $languages = LiteratureVariant::query()->where('literature_id', $variant['literature_id'])->pluck('language')->toArray();
        $variant['availableLanguages'] = implode(', ', $languages);
        $variant['category'] = $literature['category'];
        return view('read.index', ['literatureItem' => $variant]);
    }
}
