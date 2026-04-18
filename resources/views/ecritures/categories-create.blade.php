@extends('layouts.dashboard')

@section('title', 'Nouvelle Catégorie d\'écriture')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('ecritures.categories') }}" class="text-[#6B7280] hover:text-[#1F2937]">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-[#1F2937]">Nouvelle Catégorie d'écriture</h1>
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-[#FFEBEE] p-4">
                <ul class="list-disc list-inside text-sm text-[#C62828]">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('ecritures.categories.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="nom" class="mb-2 block text-sm font-medium text-[#1F2937]">
                            Tag/catégorie <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            value="{{ old('nom') }}"
                            required
                            placeholder="Ex: Salaires, Fournitures, Loyer..."
                            class="w-full rounded-lg border border-[#E5E7EB] px-4 py-2 text-sm focus:border-[#1860E1] focus:outline-none focus:ring-2 focus:ring-[#1860E1]/20"
                        >
                        <p class="mt-1 text-xs text-[#6B7280]">Le nom de la catégorie qui apparaîtra dans vos écritures</p>
                    </div>

                    <div class="rounded-lg bg-[#F9FAFB] p-4">
                        <h3 class="text-sm font-medium text-[#1F2937] mb-2">Informations</h3>
                        <p class="text-xs text-[#6B7280]">Les statistiques suivantes seront calculées automatiquement :</p>
                        <ul class="mt-2 space-y-1 text-xs text-[#6B7280]">
                            <li>• <strong>Nb</strong> : Nombre d'écritures dans cette catégorie</li>
                            <li>• <strong>Total</strong> : Somme des montants (crédit - débit)</li>
                            <li>• <strong>Moyenne</strong> : Montant moyen par écriture</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-end gap-4 border-t border-[#E5E7EB] pt-6">
                    <a 
                        href="{{ route('ecritures.categories') }}" 
                        class="rounded-lg border border-[#E5E7EB] bg-white px-6 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]"
                    >
                        Annuler
                    </a>
                    <button 
                        type="submit" 
                        class="rounded-lg bg-[#1860E1] px-6 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]"
                    >
                        Créer la catégorie
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
