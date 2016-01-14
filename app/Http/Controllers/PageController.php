<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class PageController extends Controller
{
    /**
     * Affiche la vue pages.index
     */
    public function pageIndex()
    {
        return view('pages.index');
    }
}
