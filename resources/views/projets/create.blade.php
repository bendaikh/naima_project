@extends('layouts.dashboard')

@section('title', 'Nouveau projet')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Créer un projet</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('projets.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Nom du projet</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 h-24"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Client</label>
            <select name="client_id" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Sélectionner un client</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Date de début</label>
                <input type="date" name="date_debut" value="{{ old('date_debut') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Date de fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Budget</label>
            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Créer</button>
            <a href="{{ route('projets.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
