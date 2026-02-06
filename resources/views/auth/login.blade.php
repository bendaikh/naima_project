<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-page { --primary: #1860E1; --navy: #1F2937; --navy-light: #374151; --grey: #6B7280; --grey-light: #9CA3AF; --bg-input: #F3F4F6; --white: #FFFFFF; --green: #4CAF50; }
    </style>
</head>
<body class="login-page min-h-screen flex font-sans text-[#1F2937] antialiased">
    {{-- Left panel: brand --}}
    <div class="hidden lg:flex lg:w-[44%] flex-col justify-between bg-[#1F2937] px-12 py-16">
        <div>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1860E1] text-white font-bold text-lg">G</span>
                <span class="text-xl font-semibold text-white">{{ config('app.name') }}</span>
            </a>
            <p class="mt-2 text-sm text-[#9CA3AF]">ERP SaaS Business</p>
        </div>
        <div class="space-y-6">
            <p class="text-2xl lg:text-3xl font-semibold text-white leading-tight">
                Gérez votre activité en toute simplicité.
            </p>
            <p class="text-[#9CA3AF] max-w-sm">
                Accédez à votre tableau de bord, vos factures et vos indicateurs en un seul endroit.
            </p>
            <div class="flex gap-3">
                <span class="h-2 w-2 rounded-full bg-[#1860E1]"></span>
                <span class="h-2 w-2 rounded-full bg-[#374151]"></span>
                <span class="h-2 w-2 rounded-full bg-[#374151]"></span>
            </div>
        </div>
    </div>

    {{-- Right panel: form --}}
    <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 bg-[#FFFFFF]">
        <div class="mx-auto w-full max-w-[400px]">
            <div class="lg:hidden mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1860E1] text-white font-bold">G</span>
                    <span class="font-semibold text-[#1F2937]">{{ config('app.name') }}</span>
                </a>
            </div>

            <h1 class="text-2xl font-semibold text-[#1F2937]">Connexion</h1>
            <p class="mt-1 text-sm text-[#6B7280]">Entrez vos identifiants pour accéder à votre espace.</p>

            @if ($errors->any())
                <div class="mt-6 p-4 rounded-lg bg-[#FEF2F2] border border-[#FECACA] text-sm text-[#DC2626]">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-[#374151]">Adresse email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="vous@exemple.com"
                        class="mt-1.5 block w-full rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] px-4 py-3 text-[#1F2937] placeholder-[#9CA3AF] focus:border-[#1860E1] focus:outline-none focus:ring-2 focus:ring-[#1860E1]/20 transition-colors"
                    />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-[#374151]">Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="mt-1.5 block w-full rounded-lg border border-[#E5E7EB] bg-[#F9FAFB] px-4 py-3 text-[#1F2937] placeholder-[#9CA3AF] focus:border-[#1860E1] focus:outline-none focus:ring-2 focus:ring-[#1860E1]/20 transition-colors"
                    />
                </div>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="h-4 w-4 rounded border-[#D1D5DB] text-[#1860E1] focus:ring-[#1860E1]"
                    />
                    <label for="remember" class="ml-2 text-sm text-[#6B7280]">Se souvenir de moi</label>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-lg bg-[#1860E1] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#1557C7] focus:outline-none focus:ring-2 focus:ring-[#1860E1] focus:ring-offset-2 transition-colors"
                >
                    Se connecter
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-[#9CA3AF]">
                En vous connectant, vous acceptez nos conditions d’utilisation.
            </p>
        </div>
    </div>
</body>
</html>
