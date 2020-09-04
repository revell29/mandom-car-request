<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use View;

class PageController extends Controller
{
    protected $base_path;

    protected $pages;

    public function __construct()
    { }


    public function run($slug = '')
    {
        if (!View::exists($slug)) {
            return 'not found';
        }

        return View::make($slug);
    }

    public function createPage(Request $request)
    {
        File::put(base_path('resources/page/' . $request->page . 'blade.php'), $request->page);
    }
}
