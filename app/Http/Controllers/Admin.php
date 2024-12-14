<?php

namespace App\Http\Controllers;

final class Admin extends Controller
{
  /**
   * Index page
   */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * New literature page
     */
    public function newliterature()
    {
        return view('admin.newliterature');
    }
}
