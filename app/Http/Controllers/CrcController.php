<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrcController extends Controller
{
    public function index()
    {
        return view('crc.index');
    }

    public function contacts()
    {
        return view('crc.contacts');
    }

    public function opportunites()
    {
        return view('crc.opportunites');
    }

    public function campagnes()
    {
        return view('crc.campagnes');
    }
}
