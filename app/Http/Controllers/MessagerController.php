<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessagerController extends Controller
{
    public function index()
    {
        return view('messager.index');
    }

    public function conversation($id)
    {
        return view('messager.conversation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // TODO: Save message to database
        return redirect()->back()->with('success', 'Message envoyé');
    }
}
