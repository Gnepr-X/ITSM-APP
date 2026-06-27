<?php
namespace App\Http\Controllers;

use App\Models\{Attribution, Equipement, Ressource, Site};
use Illuminate\Http\Request;

class AttributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attribution::with('equipement', 'ressource', 'site');

        if ($request->site_id) $query->where('site_id', $request->site_id);
        if ($request->statut)  $query->where('statut', $request->statut);
        if ($request->search)  $query->whereHas('ressource', fn($q) =>
            $q->where('nom', 'like', '%'.$request->search.'%')
              ->orWhere('matricule', 'like', '%'.$request->search.'%')
        );

        $attributions = $query->latest()->paginate(200)->withQueryString();
        $sites        = Site::orderBy('nom')->get();

        return view('attributions.index', compact('attributions', 'sites'));
    }

    public function create()
    {
        $equipements = Equipement::where('statut', 'disponible')->with('site')->get();
        $ressources  = Ressource::with('site')->orderBy('nom')->get();
        $sites       = Site::orderBy('nom')->get();

        return view('attributions.create', compact('equipements', 'ressources', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipement_id'    => 'required|exists:equipements,id',
            'ressource_id'     => 'required|exists:ressources,id',
            'site_id'          => 'required|exists:sites,id',
            'date_attribution' => 'required|date',
            'attribue_par'     => 'required|string|max:255',
            'observation'      => 'nullable|string',
        ]);

       // dd($validated);
        // Vérifier que l'équipement est disponible
        $equipement = Equipement::findOrFail($validated['equipement_id']);
        if ($equipement->statut !== 'disponible') {
            return back()->with('error', 'Cet équipement n\'est pas disponible.')->withInput();
        }

        Attribution::create($validated);
        return redirect()->route('attributions.index')->with('success', 'Attribution enregistrée.');
    }

    public function show(Attribution $attribution)
    {
        $attribution->load('equipement.site', 'ressource', 'site', 'restitution');
        return view('attributions.show', compact('attribution'));
    }

    public function edit(Attribution $attribution)
    {
        if ($attribution->statut === 'restitue') {
            return back()->with('error', 'Une attribution restituée ne peut pas être modifiée.');
        }

        $equipements = Equipement::whereIn('statut', ['disponible', 'attribue'])->with('site')->get();
        $ressources  = Ressource::with('site')->orderBy('nom')->get();
        $sites       = Site::orderBy('nom')->get();

        return view('attributions.edit', compact('attribution', 'equipements', 'ressources', 'sites'));
    }

    public function update(Request $request, Attribution $attribution)
    {
        $validated = $request->validate([
            'date_attribution' => 'required|date',
            'attribue_par'     => 'required|string|max:255',
            'observation'      => 'nullable|string',
        ]);

        $attribution->update($validated);
        return redirect()->route('attributions.index')->with('success', 'Attribution mise à jour.');
    }

    public function destroy(Attribution $attribution)
    {
        if ($attribution->statut === 'actif') {
            return back()->with('error', 'Veuillez d\'abord restituer l\'équipement.');
        }

        $attribution->delete();
        return redirect()->route('attributions.index')->with('success', 'Attribution supprimée.');
    }
}