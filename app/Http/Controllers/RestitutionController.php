<?php
namespace App\Http\Controllers;

use App\Models\{Restitution, Attribution};
use Illuminate\Http\Request;

class RestitutionController extends Controller
{
    public function index()
    {
        $restitutions = Restitution::with('attribution.equipement', 'attribution.ressource', 'attribution.site')
            ->latest()->paginate(20);

        return view('restitutions.index', compact('restitutions'));
    }

    public function create(Request $request)
    {
        // On peut pré-sélectionner l'attribution via ?attribution_id=
        $attribution = null;
        if ($request->attribution_id) {
            $attribution = Attribution::with('equipement', 'ressource', 'site')
                ->where('statut', 'actif')
                ->findOrFail($request->attribution_id);
        }

        $attributions_actives = Attribution::with('equipement', 'ressource')
            ->where('statut', 'actif')->get();

        return view('restitutions.create', compact('attribution', 'attributions_actives'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attribution_id'   => 'required|exists:attributions,id',
            'date_restitution' => 'required|date',
            'recu_par'         => 'required|string|max:255',
            'etat_retour'      => 'required|in:bon,acceptable,endommagé,hors_service',
            'observation'      => 'nullable|string',
        ]);

        $attribution = Attribution::findOrFail($validated['attribution_id']);

        if ($attribution->statut === 'restitue') {
            return back()->with('error', 'Cette attribution a déjà été restituée.');
        }

        if ($attribution->restitution()->exists()) {
            return back()->with('error', 'Une restitution existe déjà pour cette attribution.');
        }

        Restitution::create($validated);
        return redirect()->route('attributions.index')->with('success', 'Restitution enregistrée.');
    }

    public function show(Restitution $restitution)
    {
        $restitution->load('attribution.equipement', 'attribution.ressource', 'attribution.site');
        return view('restitutions.show', compact('restitution'));
    }

    // Pas d'edit/update : une restitution est définitive
    public function destroy(Restitution $restitution)
    {
        // Remettre l'attribution en actif et l'équipement attribué
        $restitution->attribution->update(['statut' => 'actif']);
        $restitution->attribution->equipement->update(['statut' => 'attribue']);
        $restitution->delete();

        return redirect()->route('restitutions.index')->with('success', 'Restitution annulée.');
    }
}