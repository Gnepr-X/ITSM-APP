<?php
namespace App\Http\Controllers;

use App\Models\{Intervention, Site, Ressource, Equipement};
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $query = Intervention::with('site', 'ressource', 'equipement');

        if ($request->site_id)  $query->where('site_id', $request->site_id);
        if ($request->statut)   $query->where('statut', $request->statut);
        if ($request->priorite) $query->where('priorite', $request->priorite);
        if ($request->type)     $query->where('type', $request->type);
        if ($request->search)   $query->where(function($q) use ($request) {
            $q->where('titre', 'like', '%'.$request->search.'%')
              ->orWhere('numero_ticket', 'like', '%'.$request->search.'%');
        });

        $interventions = $query->orderByRaw("FIELD(priorite, 'critique','haute','normale','basse')")
                               ->orderByRaw("FIELD(statut, 'ouvert','en_cours','resolu','ferme')")
                               ->paginate(20)->withQueryString();

        $sites = Site::orderBy('nom')->get();

        return view('interventions.index', compact('interventions', 'sites'));
    }

    public function create()
    {
        $sites       = Site::orderBy('nom')->get();
        $ressources  = Ressource::orderBy('nom')->get();
        $equipements = Equipement::with('site')->orderBy('designation')->get();

        return view('interventions.create', compact('sites', 'ressources', 'equipements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id'        => 'required|exists:sites,id',
            'ressource_id'   => 'nullable|exists:ressources,id',
            'equipement_id'  => 'nullable|exists:equipements,id',
            'type'           => 'required|in:depannage,installation,maintenance,support,autre',
            'titre'          => 'required|string|max:255',
            'description'    => 'required|string',
            'priorite'       => 'required|in:basse,normale,haute,critique',
            'date_ouverture' => 'required|date',
        ]);

        $validated['statut'] = 'ouvert';

        Intervention::create($validated);
        return redirect()->route('interventions.index')->with('success', 'Ticket créé.');
    }

    public function show(Intervention $intervention)
    {
        $intervention->load('site', 'ressource', 'equipement');
        return view('interventions.show', compact('intervention'));
    }

    public function edit(Intervention $intervention)
    {
        if ($intervention->statut === 'ferme') {
            return back()->with('error', 'Un ticket fermé ne peut pas être modifié.');
        }

        $sites       = Site::orderBy('nom')->get();
        $ressources  = Ressource::orderBy('nom')->get();
        $equipements = Equipement::with('site')->orderBy('designation')->get();

        return view('interventions.edit', compact('intervention', 'sites', 'ressources', 'equipements'));
    }

    public function update(Request $request, Intervention $intervention)
    {
        $validated = $request->validate([
            'site_id'         => 'required|exists:sites,id',
            'ressource_id'    => 'nullable|exists:ressources,id',
            'equipement_id'   => 'nullable|exists:equipements,id',
            'type'            => 'required|in:depannage,installation,maintenance,support,autre',
            'titre'           => 'required|string|max:255',
            'description'     => 'required|string',
            'priorite'        => 'required|in:basse,normale,haute,critique',
            'statut'          => 'required|in:ouvert,en_cours,resolu,ferme',
            'date_ouverture'  => 'required|date',
            'date_resolution' => 'nullable|date|after_or_equal:date_ouverture',
            'solution'        => 'nullable|string',
        ]);

        $intervention->update($validated);
        return redirect()->route('interventions.index')->with('success', 'Intervention mise à jour.');
    }

    public function destroy(Intervention $intervention)
    {
        if (!in_array($intervention->statut, ['resolu', 'ferme'])) {
            return back()->with('error', 'Seules les interventions résolues ou fermées peuvent être supprimées.');
        }

        $intervention->delete();
        return redirect()->route('interventions.index')->with('success', 'Intervention supprimée.');
    }

    // Changer le statut rapidement (boutons dans la liste)
    public function changerStatut(Request $request, Intervention $intervention)
    {
        $request->validate(['statut' => 'required|in:ouvert,en_cours,resolu,ferme']);
        $intervention->update(['statut' => $request->statut]);
        return back()->with('success', 'Statut mis à jour.');
    }
}