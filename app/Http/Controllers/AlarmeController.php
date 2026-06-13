<?php
namespace App\Http\Controllers;

use App\Models\{Alarme, Site};
use Illuminate\Http\Request;

class AlarmeController extends Controller
{
    public function index(Request $request)
    {
        $query = Alarme::with('site');

        if ($request->site_id) $query->where('site_id', $request->site_id);
        if ($request->statut)  $query->where('statut', $request->statut);

        $alarmes = $query->orderBy('site_id')->paginate(20)->withQueryString();
        $sites   = Site::orderBy('nom')->get();

        return view('alarmes.index', compact('alarmes', 'sites'));
    }

    public function create()
    {
        $sites = Site::orderBy('nom')->get();
        return view('alarmes.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'type_systeme'      => 'required|string|max:255',
            'marque'            => 'nullable|string|max:100',
            'zone_couverte'     => 'required|string|max:255',
            'code_acces'        => 'nullable|string|max:50',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif,en_panne',
        ]);

        Alarme::create($validated);
        return redirect()->route('alarmes.index')->with('success', 'Alarme ajoutée.');
    }

    public function show(Alarme $alarme)
    {
        $alarme->load('site');
        // Le code d'accès n'est visible que pour les admins
        $showCode = auth()->user()->hasRole('admin');
        return view('alarmes.show', compact('alarme', 'showCode'));
    }

    public function edit(Alarme $alarme)
    {
        $sites = Site::orderBy('nom')->get();
        return view('alarmes.edit', compact('alarme', 'sites'));
    }

    public function update(Request $request, Alarme $alarme)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'type_systeme'      => 'required|string|max:255',
            'marque'            => 'nullable|string|max:100',
            'zone_couverte'     => 'required|string|max:255',
            'code_acces'        => 'nullable|string|max:50',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif,en_panne',
        ]);

        $alarme->update($validated);
        return redirect()->route('alarmes.index')->with('success', 'Alarme mise à jour.');
    }

    public function destroy(Alarme $alarme)
    {
        $alarme->delete();
        return redirect()->route('alarmes.index')->with('success', 'Alarme supprimée.');
    }
}