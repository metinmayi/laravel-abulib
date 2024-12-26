<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class AdminController extends Controller
{
  /**
   * Index page
   */
    public function index(): View
    {
        return view('admin.index');
    }

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
    public function newvariant(): View
    {
        return view('admin.newvariant');
    }
}
