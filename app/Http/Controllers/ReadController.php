<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ReadController extends Controller
{
    /**
     * Display the reader page.
     */
    public function index(int $variantId): View
    {
        $variant = Variant::query()->findOrFail($variantId)->toArray();
        $literature = Literature::query()->findOrFail($variant['literature_id'])->toArray();
        $languages = Variant::query()
            ->where('literature_id', $variant['literature_id'])
            ->whereNot('url', null)
            ->pluck('language')->toArray();
        $variant['availableLanguages'] = $languages;
        $variant['category'] = $literature['category'];
        return view('read.index', ['literatureItem' => $variant]);
    }


    /**
     * Get literature binary. Used for PDF source.
     */
    public function getLiteratureBinary(int $id): ResponseFactory | Response
    {
        $variant = Variant::query()->find($id);
        if (!$variant || !$variant->url) {
            return response(null, 404);
        }

        $content = Storage::get($variant->url);
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
