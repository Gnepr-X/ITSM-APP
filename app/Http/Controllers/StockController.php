<?php
namespace App\Http\Controllers;

use App\Models\{Stock, Equipement, Site};
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('equipement', 'site');

        if ($request->site_id)   $query->where('site_id', $request->site_id);
        if ($request->mouvement) $query->where('mouvement', $request->mouvement);
        if ($request->search)    $query->whereHas('equipement', fn($q) =>
            $q->where('designation', 'like', '%'.$request->search.'%')
              ->orWhere('code_inventaire', 'like', '%'.$request->search.'%')
        );

        $stocks = $query->orderByDesc('date_mouvement')->paginate(20)->withQueryString();
        $sites  = Site::orderBy('nom')->get();

        // Totaux pour le résumé
        $total_entrees = Stock::where('mouvement', 'entree')->count();
        $total_sorties = Stock::where('mouvement', 'sortie')->count();

        return view('stocks.index', compact('stocks', 'sites', 'total_entrees', 'total_sorties'));
    }

    public function create()
    {
        $equipements = Equipement::with('site')->orderBy('designation')->get();
        $sites       = Site::orderBy('nom')->get();
        return view('stocks.create', compact('equipements', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipement_id'  => 'required|exists:equipements,id',
            'mouvement'      => 'required|in:entree,sortie',
            'quantite'       => 'required|integer|min:1',
            'motif'          => 'required|string|max:255',
            'site_id'        => 'required|exists:sites,id',
            'effectue_par'   => 'required|string|max:255',
            'date_mouvement' => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        Stock::create($validated);

        // Mettre à jour le statut de l'équipement selon le mouvement
        $equipement = Equipement::find($validated['equipement_id']);
        if ($validated['mouvement'] === 'entree') {
            $equipement->update(['statut' => 'disponible']);
        } elseif ($validated['mouvement'] === 'sortie') {
            $equipement->update(['statut' => 'hors_service']);
        }

        return redirect()->route('stocks.index')->with('success', 'Mouvement de stock enregistré.');
    }

    public function show(Stock $stock)
    {
        $stock->load('equipement.site', 'site');
        return view('stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        $equipements = Equipement::with('site')->orderBy('designation')->get();
        $sites       = Site::orderBy('nom')->get();
        return view('stocks.edit', compact('stock', 'equipements', 'sites'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'motif'          => 'required|string|max:255',
            'effectue_par'   => 'required|string|max:255',
            'date_mouvement' => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $stock->update($validated);
        return redirect()->route('stocks.index')->with('success', 'Mouvement mis à jour.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Mouvement supprimé.');
    }
}