<?php
namespace App\Http\Controllers;

use App\Models\{AccesWifi, Site};
use Illuminate\Http\Request;

class AccesWifiController extends Controller
{
    public function index(Request $request)
    {
        $query = AccesWifi::with('site');

        if ($request->site_id)  $query->where('site_id', $request->site_id);
        if ($request->operateur) $query->where('operateur', $request->operateur);
        if ($request->statut)   $query->where('statut', $request->statut);

        $accesWifis = $query->orderBy('site_id')->paginate(20)->withQueryString();
        $sites      = Site::orderBy('nom')->get();

        return view('acces-wifis.index', compact('accesWifis', 'sites'));
    }

    public function create()
    {
        $sites = Site::orderBy('nom')->get();
        return view('acces-wifis.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'operateur'         => 'required|in:ORANGE,MTN,AUTRE',
            'nom_box'           => 'required|string|max:255',
            'ssid'              => 'required|string|max:255',
            'mot_de_passe'      => 'required|string|max:255',
            'adresse_ip'        => 'nullable|ip',
            'adresse_mac'       => 'nullable|string|max:17',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif',
            'notes'             => 'nullable|string',
        ]);

        AccesWifi::create($validated);
        return redirect()->route('acces-wifis.index')->with('success', 'Accès WIFI ajouté.');
    }

    public function show(AccesWifi $accesWifi)
    {
        $accesWifi->load('site');
        return view('acces-wifis.show', compact('accesWifi'));
    }

    public function edit(AccesWifi $accesWifi)
    {
        $sites = Site::orderBy('nom')->get();
        return view('acces-wifis.edit', compact('accesWifi', 'sites'));
    }

    public function update(Request $request, AccesWifi $accesWifi)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'operateur'         => 'required|in:ORANGE,MTN,AUTRE',
            'nom_box'           => 'required|string|max:255',
            'ssid'              => 'required|string|max:255',
            'mot_de_passe'      => 'required|string|max:255',
            'adresse_ip'        => 'nullable|ip',
            'adresse_mac'       => 'nullable|string|max:17',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif',
            'notes'             => 'nullable|string',
        ]);

        $accesWifi->update($validated);
        return redirect()->route('acces-wifis.index')->with('success', 'Accès WIFI mis à jour.');
    }

    public function destroy(AccesWifi $accesWifi)
    {
        $accesWifi->delete();
        return redirect()->route('acces-wifis.index')->with('success', 'Accès WIFI supprimé.');
    }
}