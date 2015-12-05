<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
