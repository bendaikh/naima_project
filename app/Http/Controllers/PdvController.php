<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdvController extends Controller
{
    public function index()
    {
        return view('pdv.index');
    }

    public function caisse()
    {
        return view('pdv.caisse');
    }

    public function ventes()
    {
        return view('pdv.ventes');
    }

    public function rapportJournalier()
    {
        return view('pdv.rapport-journalier');
    }
}
