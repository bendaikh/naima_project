<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrhController extends Controller
{
    public function index()
    {
        return view('grh.index');
    }

    public function employes()
    {
        return view('grh.employes');
    }

    public function conges()
    {
        return view('grh.conges');
    }

    public function paies()
    {
        return view('grh.paies');
    }
}
