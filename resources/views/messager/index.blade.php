@extends('layouts.dashboard')

@section('title', 'Messager')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Messager</h1>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white shadow">
            <div class="border-b border-slate-200 p-4">
                <h2 class="font-semibold text-slate-800">Conversations</h2>
            </div>
            <div class="divide-y divide-slate-200">
                <div class="p-4 hover:bg-slate-50 cursor-pointer">
                    <p class="text-sm font-medium text-slate-800">Aucune conversation</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 rounded-lg border border-slate-200 bg-white shadow flex flex-col">
            <div class="border-b border-slate-200 p-4">
                <p class="text-center text-slate-500">Sélectionnez une conversation</p>
            </div>
            <div class="flex-1 p-4 text-center text-slate-500">
                <p>Aucune conversation sélectionnée</p>
            </div>
        </div>
    </div>
</div>
@endsection
