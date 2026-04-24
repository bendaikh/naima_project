@extends('layouts.dashboard')

@section('title', 'Modifier un utilisateur')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-[#1F2937]">Modifier un utilisateur</h1>
        <form method="post" action="{{ route('users.update', $user) }}" class="space-y-4 rounded-xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-[#374151]">Nom *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('name')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-[#374151]">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                @error('email')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
            </div>
            <div class="rounded-lg bg-[#FEF3C7] p-4 border border-[#FDE68A]">
                <p class="text-sm font-medium text-[#92400E] mb-3">Modifier le mot de passe (optionnel)</p>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#374151]">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                        @error('password')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-[#6B7280]">Laissez vide pour conserver le mot de passe actuel. Minimum 8 caractères si vous le modifiez.</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#374151]">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    </div>
                </div>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-[#374151]">Rôle *</label>
                <select name="role" id="role" required class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-4 py-2 focus:border-[#1860E1] focus:ring-1 focus:ring-[#1860E1]">
                    <option value="">Sélectionner un rôle</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')<p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-[#1860E1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1557C7]">Enregistrer</button>
                <a href="{{ route('users.index') }}" class="rounded-lg border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB]">Annuler</a>
            </div>
        </form>
    </div>
@endsection
