<?php
namespace App\Http\Controllers;

use App\Models\{Site, Ressource, Equipement, Attribution, Intervention, Camera, Alarme, AccesWifi, Stock};

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            // Équipements
            'total_equipements'      => Equipement::count(),
            'disponibles'            => Equipement::where('statut', 'disponible')->count(),
            'attribues'              => Equipement::where('statut', 'attribue')->count(),
            'en_reparation'          => Equipement::where('statut', 'en_reparation')->count(),
            'hors_service'           => Equipement::where('statut', 'hors_service')->count(),

            // Interventions
            'interventions_ouvertes'  => Intervention::whereIn('statut', ['ouvert', 'en_cours'])->count(),
            'interventions_critiques' => Intervention::where('priorite', 'critique')
                                                      ->where('statut', '!=', 'ferme')->count(),
            'interventions_resolues'  => Intervention::where('statut', 'resolu')
                                                      ->whereMonth('date_resolution', now()->month)->count(),

            // Infra
            'total_sites'            => Site::count(),
            'total_ressources'       => Ressource::count(),
            'cameras_actives'        => Camera::where('statut', 'actif')->count(),
            'alarmes_actives'        => Alarme::where('statut', 'actif')->count(),
            'acces_wifis_actifs'     => AccesWifi::where('statut', 'actif')->count(),

            // Listes récentes
            'derniers_mouvements'       => Stock::with('equipement', 'site')->latest()->take(8)->get(),
            'dernieres_interventions'   => Intervention::with('site', 'ressource')
                                                        ->whereIn('statut', ['ouvert', 'en_cours'])
                                                        ->orderBy('priorite')->latest()->take(6)->get(),
            'dernieres_attributions'    => Attribution::with('equipement', 'ressource', 'site')
                                                       ->latest()->take(5)->get(),

            // Stats par site
            'equipements_par_site'   => Site::withCount('equipements')->get(),
            'interventions_par_site' => Site::withCount(['interventions' => fn($q) =>
                                            $q->whereIn('statut', ['ouvert', 'en_cours'])])->get(),
        ]);
    }
}