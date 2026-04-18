@extends('layouts.dashboard')

@section('title', 'Nouveau virement interne')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[#1F2937]">Nouveau virement interne</h1>
            <a href="{{ route('virement-interne.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour
            </a>
        </div>

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('virement-interne.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="de_compte_id" class="mb-2 block text-sm font-medium text-[#1F2937]">Compte source <span class="text-red-500">*</span></label>
                        <select id="de_compte_id" name="de_compte_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('de_compte_id') border-red-500 @enderror">
                            <option value="">Sélectionner un compte</option>
                            @foreach($banques as $banque)
                                <option value="{{ $banque->id }}" {{ old('de_compte_id') == $banque->id ? 'selected' : '' }}>{{ $banque->libelle }} ({{ number_format($banque->solde_actuel, 2, ',', ' ') }} {{ $banque->devise }})</option>
                            @endforeach
                        </select>
                        @error('de_compte_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vers_compte_id" class="mb-2 block text-sm font-medium text-[#1F2937]">Compte destination <span class="text-red-500">*</span></label>
                        <select id="vers_compte_id" name="vers_compte_id" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('vers_compte_id') border-red-500 @enderror">
                            <option value="">Sélectionner un compte</option>
                            @foreach($banques as $banque)
                                <option value="{{ $banque->id }}" {{ old('vers_compte_id') == $banque->id ? 'selected' : '' }}>{{ $banque->libelle }} ({{ number_format($banque->solde_actuel, 2, ',', ' ') }} {{ $banque->devise }})</option>
                            @endforeach
                        </select>
                        @error('vers_compte_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="montant" class="mb-2 block text-sm font-medium text-[#1F2937]">Montant <span class="text-red-500">*</span></label>
                        <input type="number" id="montant" name="montant" value="{{ old('montant') }}" step="0.01" min="0" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('montant') border-red-500 @enderror">
                        @error('montant')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="mb-2 block text-sm font-medium text-[#1F2937]">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="type" class="mb-2 block text-sm font-medium text-[#1F2937]">Type de virement</label>
                        <input type="text" id="type" name="type" value="{{ old('type', 'Virement bancaire') }}" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('type') border-red-500 @enderror">
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-[#1F2937]">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('virement-interne.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
                    <button type="submit" class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Effectuer le virement</button>
                </div>
            </form>
        </div>
    </div>
@endsection
