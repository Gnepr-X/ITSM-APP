<?php
namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::withCount([
            'equipements', 'ressources', 'cameras',
            'alarmes', 'accesWifis',
            'interventions' => fn($q) => $q->whereIn('statut', ['ouvert', 'en_cours'])
        ])->paginate(20);

        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:255|unique:sites',
            'ville'        => 'required|string|max:255',
            'adresse'      => 'nullable|string|max:500',
            'responsable'  => 'nullable|string|max:255',
            'telephone'    => 'nullable|string|max:20',
        ]);

        Site::create($validated);
        return redirect()->route('sites.index')->with('success', 'Site créé avec succès.');
    }

    public function show(Site $site)
    {
        $site->load([
            'equipements', 'ressources', 'cameras',
            'alarmes', 'accesWifis',
            'interventions' => fn($q) => $q->latest()->take(10)
        ]);

        $stats = [
            'equipements_disponibles' => $site->equipements->where('statut', 'disponible')->count(),
            'equipements_attribues'   => $site->equipements->where('statut', 'attribue')->count(),
            'equipements_en_panne'    => $site->equipements->whereIn('statut', ['en_reparation','hors_service'])->count(),
            'interventions_ouvertes'  => $site->interventions->whereIn('statut', ['ouvert','en_cours'])->count(),
        ];

        return view('sites.show', compact('site', 'stats'));
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255|unique:sites,nom,' . $site->id,
            'ville'       => 'required|string|max:255',
            'adresse'     => 'nullable|string|max:500',
            'responsable' => 'nullable|string|max:255',
            'telephone'   => 'nullable|string|max:20',
        ]);

        $site->update($validated);
        return redirect()->route('sites.index')->with('success', 'Site mis à jour.');
    }

    public function destroy(Site $site)
    {
        if ($site->equipements()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : ce site possède des équipements.');
        }

        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site supprimé.');
    }
}