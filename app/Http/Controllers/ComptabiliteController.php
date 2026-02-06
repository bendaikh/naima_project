<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComptabiliteController extends Controller
{
    public function index()
    {
        return view('comptabilite.index');
    }

    public function journalGeneral()
    {
        return view('comptabilite.journal-general');
    }

    public function bilan()
    {
        return view('comptabilite.bilan');
    }

    public function resultat()
    {
        return view('comptabilite.resultat');
    }

    public function tva()
    {
        return view('comptabilite.tva');
    }
}
