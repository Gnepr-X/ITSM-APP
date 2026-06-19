<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, SiteController, RessourceController,
    EquipementController, AttributionController, RestitutionController,
    AccesWifiController, CameraController, AlarmeController,
    InterventionController, StockController, PdfController
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'sites'        => SiteController::class,
        'ressources'   => RessourceController::class,
        'equipements'  => EquipementController::class,
        'attributions' => AttributionController::class,
        'restitutions' => RestitutionController::class,
        'acces-wifis'  => AccesWifiController::class,
        'cameras'      => CameraController::class,
        'alarmes'      => AlarmeController::class,
        'interventions'=> InterventionController::class,
        'stocks'       => StockController::class,
    ]);

    // Routes PDF
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('attribution/{attribution}',  [PdfController::class, 'ficheAttribution'])->name('attribution');
        Route::get('restitution/{attribution}',  [PdfController::class, 'ficheRestitution'])->name('restitution');
        Route::get('inventaire',                 [PdfController::class, 'ficheInventaire'])->name('inventaire');
        Route::get('acces-wifi/{site}',          [PdfController::class, 'ficheAccesWifi'])->name('acces_wifi');
        Route::get('ressources/{site?}',         [PdfController::class, 'ficheRessources'])->name('ressources');
        Route::get('interventions',              [PdfController::class, 'ficheInterventions'])->name('interventions');
    });

    // ✅ Ajouter ces routes supplémentaires pour les équipements
            Route::get('equipements/{equipement}/qrcode',
                [EquipementController::class, 'qrCode']
            )->name('equipements.qrcode');

            Route::post('equipements/{equipement}/regenerer-qr',
                [EquipementController::class, 'regenererQr']
            )->name('equipements.regenerer-qr');

            // Route statut intervention
            Route::patch('interventions/{intervention}/statut',[InterventionController::class, 'changerStatut'])->name('interventions.statut');
});

// Route publique pour la consultation via QR code (pas d'authentification requise)
Route::get('equipements/info/{code}',
    [EquipementController::class, 'publicInfo']
)->name('equipements.public-info');

require __DIR__.'/auth.php';
