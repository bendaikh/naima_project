@extends('layouts.dashboard')

@section('title', 'Créer un ticket')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Créer un ticket d'assistance</h1>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('support.store') }}" class="space-y-6 rounded-lg border border-slate-200 bg-white p-6 shadow">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Sujet</label>
            <input type="text" name="sujet" value="{{ old('sujet') }}" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2 h-32"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Priorité</label>
            <select name="priorite" required class="mt-1 block w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Sélectionner une priorité</option>
                <option value="basse">Basse</option>
                <option value="normale">Normale</option>
                <option value="haute">Haute</option>
                <option value="critique">Critique</option>
            </select>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Créer</button>
            <a href="{{ route('support.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
