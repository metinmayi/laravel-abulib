<?php

namespace App\Http\Controllers;

use App\Models\LitteratureVariant as ModelsLitteratureVariant;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LitteratureVariant extends Controller
{
    public function getLitteratureBinary(int $id): BinaryFileResponse | ResponseFactory | Response
    {
        $variant = ModelsLitteratureVariant::find($id);
        if (!$variant) {
            return response(null, 404);
        }

        $pdf = Storage::disk('local')->path('smallPdf.pdf');
        return response()->file($pdf);
    }
}
