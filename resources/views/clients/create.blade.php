@extends('layouts.dashboard')

@section('title', 'Ajouter un client')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Ajouter un client</h1>
        <form method="post" action="{{ route('clients.store') }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            <div>
                <label for="nom_raison_sociale" class="block text-sm font-medium text-[#374151]">Nom / Raison sociale *</label>
                <input type="text" name="nom_raison_sociale" id="nom_raison_sociale" value="{{ old('nom_raison_sociale') }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('nom_raison_sociale')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="telephone" class="block text-sm font-medium text-[#374151]">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-[#374151]">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('email')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="adresse" class="block text-sm font-medium text-[#374151]">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">{{ old('adresse') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Enregistrer</button>
                <a href="{{ route('clients.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>
@endsection
