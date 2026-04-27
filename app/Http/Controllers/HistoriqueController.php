<?php

namespace App\Http\Controllers;

use App\Models\ActionHistory;
use Illuminate\View\View;

class HistoriqueController extends Controller
{
    public function index(): View
    {
        $historiques = ActionHistory::query()
            ->with('user')
            ->latest('action_date')
            ->paginate(20);

        return view('historique.index', compact('historiques'));
    }
}
