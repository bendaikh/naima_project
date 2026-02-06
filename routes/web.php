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

    // Existing resources
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('fournisseurs', \App\Http\Controllers\FournisseurController::class)->except(['show']);
    Route::resource('devis', \App\Http\Controllers\DevisController::class);
    Route::resource('factures', \App\Http\Controllers\FactureController::class);

    // Avoir routes (under facture section)
    Route::prefix('avoir')->name('avoir.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AvoirController::class, 'index'])->name('index');
        Route::get('{avoir}', [\App\Http\Controllers\AvoirController::class, 'show'])->name('show');
        Route::post('{avoir}/emit', [\App\Http\Controllers\AvoirController::class, 'emit'])->name('emit');
        Route::post('{avoir}/apply', [\App\Http\Controllers\AvoirController::class, 'apply'])->name('apply');
        Route::delete('{avoir}', [\App\Http\Controllers\AvoirController::class, 'destroy'])->name('destroy');
        Route::get('facture/{facture}', [\App\Http\Controllers\AvoirController::class, 'getForFacture'])->name('getForFacture');
    });
    Route::get('rapports', [\App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
    Route::get('parametres', [\App\Http\Controllers\ParametresController::class, 'index'])->name('parametres.index');
    Route::put('parametres', [\App\Http\Controllers\ParametresController::class, 'update'])->name('parametres.update');

    // New resources
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('articles', \App\Http\Controllers\ArticleController::class);
    Route::resource('bon-livraison', \App\Http\Controllers\BonLivraisonController::class);
    Route::resource('bon-retour', \App\Http\Controllers\BonRetourController::class);
    Route::get('bon-retour/delivery/{deliveryId}', [\App\Http\Controllers\BonRetourController::class, 'getForDelivery'])->name('bon-retour.getForDelivery');
    Route::resource('achats', \App\Http\Controllers\AchatController::class);
    Route::resource('projets', \App\Http\Controllers\ProjetController::class);
    
    // Comptabilité routes
    Route::prefix('comptabilite')->name('comptabilite.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ComptabiliteController::class, 'index'])->name('index');
        Route::get('journal-general', [\App\Http\Controllers\ComptabiliteController::class, 'journalGeneral'])->name('journal-general');
        Route::get('bilan', [\App\Http\Controllers\ComptabiliteController::class, 'bilan'])->name('bilan');
        Route::get('resultat', [\App\Http\Controllers\ComptabiliteController::class, 'resultat'])->name('resultat');
        Route::get('tva', [\App\Http\Controllers\ComptabiliteController::class, 'tva'])->name('tva');
    });

    // GRH routes
    Route::prefix('grh')->name('grh.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GrhController::class, 'index'])->name('index');
        Route::get('employes', [\App\Http\Controllers\GrhController::class, 'employes'])->name('employes');
        Route::get('conges', [\App\Http\Controllers\GrhController::class, 'conges'])->name('conges');
        Route::get('paies', [\App\Http\Controllers\GrhController::class, 'paies'])->name('paies');
    });

    // PDV routes
    Route::prefix('pdv')->name('pdv.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PdvController::class, 'index'])->name('index');
        Route::get('caisse', [\App\Http\Controllers\PdvController::class, 'caisse'])->name('caisse');
        Route::get('ventes', [\App\Http\Controllers\PdvController::class, 'ventes'])->name('ventes');
        Route::get('rapport-journalier', [\App\Http\Controllers\PdvController::class, 'rapportJournalier'])->name('rapport-journalier');
    });

    // CRC routes
    Route::prefix('crc')->name('crc.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CrcController::class, 'index'])->name('index');
        Route::get('contacts', [\App\Http\Controllers\CrcController::class, 'contacts'])->name('contacts');
        Route::get('opportunites', [\App\Http\Controllers\CrcController::class, 'opportunites'])->name('opportunites');
        Route::get('campagnes', [\App\Http\Controllers\CrcController::class, 'campagnes'])->name('campagnes');
    });

    // Messager routes
    Route::prefix('messager')->name('messager.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessagerController::class, 'index'])->name('index');
        Route::get('conversation/{id}', [\App\Http\Controllers\MessagerController::class, 'conversation'])->name('conversation');
        Route::post('store', [\App\Http\Controllers\MessagerController::class, 'store'])->name('store');
    });

    // Support routes
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SupportController::class, 'index'])->name('index');
        Route::get('create', [\App\Http\Controllers\SupportController::class, 'create'])->name('create');
        Route::post('store', [\App\Http\Controllers\SupportController::class, 'store'])->name('store');
        Route::get('{id}', [\App\Http\Controllers\SupportController::class, 'show'])->name('show');
        Route::put('{id}', [\App\Http\Controllers\SupportController::class, 'update'])->name('update');
    });
});
