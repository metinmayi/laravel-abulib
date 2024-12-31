<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Contracts\View\View;

class ReadController extends Controller
{
    /**
     * Display the reader page.
     */
    public function index(int $variantId): View
    {
        $variant = Variant::query()->findOrFail($variantId)->toArray();
        $literature = Literature::query()->findOrFail($variant['literature_id'])->toArray();
        $languages = Variant::query()->where('literature_id', $variant['literature_id'])->pluck('language')->toArray();
        $variant['availableLanguages'] = implode(', ', $languages);
        $variant['category'] = $literature['category'];
        return view('read.index', ['literatureItem' => $variant]);
    }
}
