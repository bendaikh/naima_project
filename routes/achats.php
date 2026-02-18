<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BonDeCommandeController;
use App\Http\Controllers\BonRetourFournisseurController;
use App\Http\Controllers\AvoirFournisseurController;

// Purchase Orders (Bon de Commande)
Route::prefix('achats/bon-de-commande')->name('achats.bon-de-commande.')->group(function () {
    Route::get('/', [BonDeCommandeController::class, 'index'])->name('index');
    Route::get('/create', [BonDeCommandeController::class, 'create'])->name('create');
    Route::post('/', [BonDeCommandeController::class, 'store'])->name('store');
    Route::get('/{bonDeCommande}', [BonDeCommandeController::class, 'show'])->name('show');
    Route::get('/{bonDeCommande}/edit', [BonDeCommandeController::class, 'edit'])->name('edit');
    Route::put('/{bonDeCommande}', [BonDeCommandeController::class, 'update'])->name('update');
    Route::post('/{bonDeCommande}/confirm', [BonDeCommandeController::class, 'confirm'])->name('confirm');
    Route::get('/{bonDeCommande}/receive', [BonDeCommandeController::class, 'showReceiveForm'])->name('receive-form');
    Route::post('/{bonDeCommande}/receive', [BonDeCommandeController::class, 'receive'])->name('receive');
    Route::delete('/{bonDeCommande}', [BonDeCommandeController::class, 'destroy'])->name('destroy');
});

// Supplier Returns (Bon de Retour Fournisseur)
Route::prefix('achats/bon-retour-fournisseur')->name('achats.bon-retour-fournisseur.')->group(function () {
    Route::get('/', [BonRetourFournisseurController::class, 'index'])->name('index');
    Route::get('/create', [BonRetourFournisseurController::class, 'create'])->name('create');
    Route::post('/', [BonRetourFournisseurController::class, 'store'])->name('store');
    Route::get('/{bonRetour}', [BonRetourFournisseurController::class, 'show'])->name('show');
    Route::post('/{bonRetour}/complete', [BonRetourFournisseurController::class, 'complete'])->name('complete');
    Route::post('/{bonRetour}/process-replacement', [BonRetourFournisseurController::class, 'processReplacement'])->name('process-replacement');
    Route::delete('/{bonRetour}', [BonRetourFournisseurController::class, 'destroy'])->name('destroy');
});

// Supplier Credit Notes (Avoir Fournisseur)
Route::prefix('achats/avoir-fournisseur')->name('achats.avoir-fournisseur.')->group(function () {
    Route::get('/', [AvoirFournisseurController::class, 'index'])->name('index');
    Route::get('/{avoir}', [AvoirFournisseurController::class, 'show'])->name('show');
    Route::post('/{avoir}/mark-received', [AvoirFournisseurController::class, 'markAsReceived'])->name('mark-received');
    Route::delete('/{avoir}', [AvoirFournisseurController::class, 'destroy'])->name('destroy');
});

// AJAX route for getting bon de commande data
Route::get('/achats/bon-de-commande/{bonDeCommande}/data', [BonRetourFournisseurController::class, 'getBonDeCommandeData'])
    ->name('achats.bon-de-commande.data');
