<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Contracts\View\View;

final class AdminController extends Controller
{
    /**
     * New variant page
     */
    public function newvariant(int $literatureId): View
    {
        $literature = Literature::query()->findOrFail($literatureId);
        return view('admin.newvariant', ['literature_id' => $literature->id]);
    }
}
