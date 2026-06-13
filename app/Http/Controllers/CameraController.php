<?php
namespace App\Http\Controllers;

use App\Models\{Camera, Site};
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function index(Request $request)
    {
        $query = Camera::with('site');

        if ($request->site_id) $query->where('site_id', $request->site_id);
        if ($request->statut)  $query->where('statut', $request->statut);
        if ($request->search)  $query->where(function($q) use ($request) {
            $q->where('reference', 'like', '%'.$request->search.'%')
              ->orWhere('emplacement', 'like', '%'.$request->search.'%');
        });

        $cameras = $query->orderBy('site_id')->paginate(20)->withQueryString();
        $sites   = Site::orderBy('nom')->get();

        return view('cameras.index', compact('cameras', 'sites'));
    }

    public function create()
    {
        $sites = Site::orderBy('nom')->get();
        return view('cameras.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'reference'         => 'required|string|max:100|unique:cameras',
            'marque'            => 'nullable|string|max:100',
            'modele'            => 'nullable|string|max:100',
            'emplacement'       => 'required|string|max:255',
            'adresse_ip'        => 'nullable|ip',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif,en_panne',
        ]);

        Camera::create($validated);
        return redirect()->route('cameras.index')->with('success', 'Caméra ajoutée.');
    }

    public function show(Camera $camera)
    {
        $camera->load('site');
        return view('cameras.show', compact('camera'));
    }

    public function edit(Camera $camera)
    {
        $sites = Site::orderBy('nom')->get();
        return view('cameras.edit', compact('camera', 'sites'));
    }

    public function update(Request $request, Camera $camera)
    {
        $validated = $request->validate([
            'site_id'           => 'required|exists:sites,id',
            'reference'         => 'required|string|max:100|unique:cameras,reference,' . $camera->id,
            'marque'            => 'nullable|string|max:100',
            'modele'            => 'nullable|string|max:100',
            'emplacement'       => 'required|string|max:255',
            'adresse_ip'        => 'nullable|ip',
            'date_installation' => 'nullable|date',
            'statut'            => 'required|in:actif,inactif,en_panne',
        ]);

        $camera->update($validated);
        return redirect()->route('cameras.index')->with('success', 'Caméra mise à jour.');
    }

    public function destroy(Camera $camera)
    {
        $camera->delete();
        return redirect()->route('cameras.index')->with('success', 'Caméra supprimée.');
    }
}