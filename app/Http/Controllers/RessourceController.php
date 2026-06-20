<?php
namespace App\Http\Controllers;

use App\Models\{Ressource, Site};
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Ressource::with('site');

        if ($request->site_id)    $query->where('site_id', $request->site_id);
        if ($request->departement) $query->where('departement', $request->departement);
        if ($request->search)      $query->where(function($q) use ($request) {
            $q->where('nom', 'like', '%'.$request->search.'%')
              ->orWhere('prenom', 'like', '%'.$request->search.'%')
              ->orWhere('matricule', 'like', '%'.$request->search.'%');
        });

        $ressources    = $query->orderBy('nom')->paginate(200)->withQueryString();
        $sites         = Site::orderBy('nom')->get();
        $departements  = Ressource::distinct()->pluck('departement')->filter();

        return view('ressources.index', compact('ressources', 'sites', 'departements'));
    }

    public function create()
    {
        $sites = Site::orderBy('nom')->get();
        return view('ressources.create', compact('sites'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'prenom'      => 'required|string|max:255',
            'matricule'   => 'required|digits:4|integer', // l'utilisateur saisit juste l'année
            'poste'       => 'required|string|max:255',
            'departement' => 'nullable|string|max:255',
            'site_id'     => 'required|exists:sites,id',
            'email'       => 'nullable|email|max:255',
            'telephone'   => 'nullable|string|max:20',
        ]);

        $ordre = Ressource::count() + 1;

        // On écrase la valeur saisie (l'année) par le matricule formaté
        $validated['matricule'] = 'LD-' . str_pad($ordre, 3, '0', STR_PAD_LEFT) . $request->matricule;

        Ressource::create($validated);
        return redirect()->route('ressources.index')->with('success', 'Ressource créée.');
    }

    public function show(Ressource $ressource)
    {
        $ressource->load([
            'site',
            'attributions' => fn($q) => $q->with('equipement')->latest(),
            'interventions' => fn($q) => $q->with('site')->latest()->take(10),
        ]);

        $equipements_actifs = $ressource->attributions
            ->where('statut', 'actif')
            ->map(fn($a) => $a->equipement);

        return view('ressources.show', compact('ressource', 'equipements_actifs'));
    }

    public function edit(Ressource $ressource)
    {
        $sites = Site::orderBy('nom')->get();
        return view('ressources.edit', compact('ressource', 'sites'));
    }

    public function update(Request $request, Ressource $ressource)
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'prenom'      => 'required|string|max:255',
            'matricule'   => 'required|string|max:50|unique:ressources,matricule,' . $ressource->id,
            'poste'       => 'required|string|max:255',
            'departement' => 'nullable|string|max:255',
            'site_id'     => 'required|exists:sites,id',
            'email'       => 'nullable|email|max:255',
            'telephone'   => 'nullable|string|max:20',
        ]);

        $ressource->update($validated);
        return redirect()->route('ressources.index')->with('success', 'Ressource mise à jour.');
    }

    public function destroy(Ressource $ressource)
    {
        if ($ressource->attributions()->where('statut', 'actif')->count() > 0) {
            return back()->with('error', 'Cette ressource possède des équipements attribués actifs.');
        }

        $ressource->delete();
        return redirect()->route('ressources.index')->with('success', 'Ressource supprimée.');
    }
}