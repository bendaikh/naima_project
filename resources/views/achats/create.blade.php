@extends('layouts.dashboard')

@section('title', 'Ajouter un achat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Ajouter un achat</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('achats.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Numéro</label>
            <input type="text" name="numero" value="{{ old('numero') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Fournisseur</label>
            <select name="fournisseur_id" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Sélectionner un fournisseur</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Date</label>
            <input type="date" name="date" value="{{ old('date') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Montant</label>
            <input type="number" step="0.01" name="montant" value="{{ old('montant') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Créer</button>
            <a href="{{ route('achats.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
