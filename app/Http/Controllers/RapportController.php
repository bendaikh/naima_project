<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RapportController extends Controller
{
    public function index(): View
    {
        return view('rapports.index');
    }
}
