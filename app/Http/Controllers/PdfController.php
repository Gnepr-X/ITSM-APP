<?php
namespace App\Http\Controllers;

use App\Models\{Attribution, Equipement, AccesWifi, Site, Ressource, Intervention};
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    // Fiche d'attribution
    public function ficheAttribution(Attribution $attribution)
    {
        $attribution->load('equipement.site','ressource','site');
        $pdf = Pdf::loadView('pdf.fiche_attribution', compact('attribution'))
                  ->setPaper('a4','portrait');
        return $pdf->stream('attribution_'.$attribution->numero_fiche.'.pdf');
    }

    // Fiche de restitution
    public function ficheRestitution(Attribution $attribution)
    {
        $attribution->load('equipement','ressource','restitution');
        $pdf = Pdf::loadView('pdf.fiche_restitution', compact('attribution'))
                  ->setPaper('a4','portrait');
        return $pdf->stream('restitution_'.$attribution->numero_fiche.'.pdf');
    }

    // Inventaire général ou par site
    public function ficheInventaire(Request $request)
    {
        $query = Equipement::with('site','attributions.ressource');
        if ($request->site_id) $query->where('site_id', $request->site_id);
        $equipements = $query->orderBy('site_id')->get();
        $site = $request->site_id ? Site::find($request->site_id) : null;
        $pdf = Pdf::loadView('pdf.fiche_inventaire', compact('equipements','site'))
                  ->setPaper('a4','landscape');
        return $pdf->stream('inventaire_'.date('Ymd').'.pdf');
    }

    // Fiche accès internet par localité
    public function ficheAccesWifi(Site $site)
    {
        $accesWifis = AccesWifi::where('site_id', $site->id)->get();
        $pdf = Pdf::loadView('pdf.fiche_acces_wifi', compact('accesWifis','site'))
                  ->setPaper('a4','portrait');
        return $pdf->stream('acces_wifi_'.$site->nom.'.pdf');
    }

    // Fiche des ressources
    public function ficheRessources(Site $site = null)
    {
        $ressources = Ressource::with('site','attributions.equipement')
            ->when($site, fn($q) => $q->where('site_id', $site->id))
            ->get();
        $pdf = Pdf::loadView('pdf.fiche_ressources', compact('ressources','site'))
                  ->setPaper('a4','portrait');
        return $pdf->stream('ressources_'.date('Ymd').'.pdf');
    }

    // Fiche des interventions
    public function ficheInterventions(Request $request)
    {
        $interventions = Intervention::with('site','ressource','equipement')
            ->when($request->site_id, fn($q) => $q->where('site_id', $request->site_id))
            ->when($request->statut,  fn($q) => $q->where('statut', $request->statut))
            ->orderByDesc('date_ouverture')->get();
        $pdf = Pdf::loadView('pdf.fiche_interventions', compact('interventions'))
                  ->setPaper('a4','landscape');
        return $pdf->stream('interventions_'.date('Ymd').'.pdf');
    }
}