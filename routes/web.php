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
    Route::resource('devis', \App\Http\Controllers\DevisController::class)->parameter('devis', 'devis');
    Route::resource('factures', \App\Http\Controllers\FactureController::class);
    Route::post('factures/{facture}/mark-as-paid', [\App\Http\Controllers\FactureController::class, 'markAsPaid'])->name('factures.mark-as-paid');
    Route::resource('articles', \App\Http\Controllers\ArticleController::class);
    Route::resource('bon-livraison', \App\Http\Controllers\BonLivraisonController::class);
    Route::post('bon-livraison/{bonLivraison}/validate', [\App\Http\Controllers\BonLivraisonController::class, 'validateBon'])->name('bon-livraison.validate');

    // Devis status actions
    Route::post('devis/{devis}/mark-as-sent', [\App\Http\Controllers\DevisController::class, 'markAsSent'])->name('devis.mark-as-sent');
    Route::post('devis/{devis}/mark-as-accepted', [\App\Http\Controllers\DevisController::class, 'markAsAccepted'])->name('devis.mark-as-accepted');
    Route::post('devis/{devis}/mark-as-refused', [\App\Http\Controllers\DevisController::class, 'markAsRefused'])->name('devis.mark-as-refused');
    Route::post('devis/{devis}/convert-to-facture', [\App\Http\Controllers\DevisController::class, 'convertToFacture'])->name('devis.convert-to-facture');

    // Avoir routes (under facture section)
    Route::prefix('avoir')->name('avoir.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AvoirController::class, 'index'])->name('index');
        Route::get('{avoir}', [\App\Http\Controllers\AvoirController::class, 'show'])->name('show');
        Route::post('{avoir}/emit', [\App\Http\Controllers\AvoirController::class, 'emit'])->name('emit');
        Route::post('{avoir}/apply', [\App\Http\Controllers\AvoirController::class, 'apply'])->name('apply');
        Route::delete('{avoir}', [\App\Http\Controllers\AvoirController::class, 'destroy'])->name('destroy');
        Route::get('facture/{facture}', [\App\Http\Controllers\AvoirController::class, 'getForFacture'])->name('getForFacture');
    });

    // Bon de Retour routes
    Route::resource('bon-retour', \App\Http\Controllers\BonRetourController::class);
    Route::get('bon-retour/delivery/{deliveryId}', [\App\Http\Controllers\BonRetourController::class, 'getForDelivery'])->name('bon-retour.getForDelivery');

    // Stock Mouvement routes
    Route::prefix('stock-mouvements')->name('stock-mouvements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\StockMouvementController::class, 'index'])->name('index');
        Route::get('{mouvement}', [\App\Http\Controllers\StockMouvementController::class, 'show'])->name('show');
        Route::get('article/{article}', [\App\Http\Controllers\StockMouvementController::class, 'article'])->name('article');
        Route::get('reference', [\App\Http\Controllers\StockMouvementController::class, 'reference'])->name('reference');
        Route::post('adjustment', [\App\Http\Controllers\StockMouvementController::class, 'adjustment'])->name('adjustment');
        Route::get('export', [\App\Http\Controllers\StockMouvementController::class, 'export'])->name('export');
    });

    // Original pages
    Route::get('rapports', [\App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
    Route::get('parametres', [\App\Http\Controllers\ParametresController::class, 'index'])->name('parametres.index');
    Route::put('parametres', [\App\Http\Controllers\ParametresController::class, 'update'])->name('parametres.update');

    // Categories management
    Route::post('categories', [\App\Http\Controllers\ParametresController::class, 'storeCategory'])->name('categories.store');
    Route::put('categories/{categorie}', [\App\Http\Controllers\ParametresController::class, 'updateCategory'])->name('categories.update');
    Route::delete('categories/{categorie}', [\App\Http\Controllers\ParametresController::class, 'deleteCategory'])->name('categories.destroy');
});
