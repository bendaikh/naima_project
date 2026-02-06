<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('fournisseurs', \App\Http\Controllers\FournisseurController::class)->except(['show']);
    Route::resource('devis', \App\Http\Controllers\DevisController::class);
    Route::resource('factures', \App\Http\Controllers\FactureController::class);
    Route::get('rapports', [\App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
    Route::get('parametres', [\App\Http\Controllers\ParametresController::class, 'index'])->name('parametres.index');
    Route::put('parametres', [\App\Http\Controllers\ParametresController::class, 'update'])->name('parametres.update');
});
