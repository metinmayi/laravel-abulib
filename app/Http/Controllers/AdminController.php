<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Contracts\View\View;

final class AdminController extends Controller
{
    /**
     * New literature page
     */
    public function newliterature(): View
    {
        return view('admin.newliterature');
    }

    /**
     * New variant page
     */
    public function newvariant(int $literatureId): View
    {
        $literature = Literature::query()->findOrFail($literatureId);
        return view('admin.newvariant', ['literature_id' => $literature->id]);
    }

    /**
     * Edit literature page
     */
    public function editVariant(int $id): View
    {
        return view('admin.editvariant', ['variant' => LiteratureVariant::find($id)]);
    }
}
