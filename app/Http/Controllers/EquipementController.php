<?php
namespace App\Http\Controllers;

use App\Models\{Equipement, Site};
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EquipementController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipement::with('site');

        if ($request->site_id) $query->where('site_id', $request->site_id);
        if ($request->type)    $query->where('type', $request->type);
        if ($request->statut)  $query->where('statut', $request->statut);
        if ($request->search)  $query->where(function($q) use ($request) {
            $q->where('designation', 'like', '%'.$request->search.'%')
              ->orWhere('code_inventaire', 'like', '%'.$request->search.'%')
              ->orWhere('numero_serie', 'like', '%'.$request->search.'%');
        });

        $equipements = $query->orderBy('site_id')->orderBy('type')->paginate(20)->withQueryString();
        $sites       = Site::orderBy('nom')->get();

        return view('equipements.index', compact('equipements', 'sites'));
    }

    public function create()
    {
        $sites = Site::orderBy('nom')->get();
        return view('equipements.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation'      => 'required|string|max:255',
            'type'             => 'required|in:ordinateur,imprimante,serveur,switch,routeur,camera,alarme,autre',
            'marque'           => 'nullable|string|max:100',
            'modele'           => 'nullable|string|max:100',
            'numero_serie'     => 'nullable|string|max:100|unique:equipements',
            'site_id'          => 'required|exists:sites,id',
            'date_acquisition' => 'nullable|date',
            'valeur'           => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $prefixe = strtoupper(substr($validated['type'], 0, 3));
        $validated['code_inventaire'] = 'EQ-' . $prefixe . '-'
            . str_pad(Equipement::count() + 1, 5, '0', STR_PAD_LEFT);

        Equipement::create($validated);
        return redirect()->route('equipements.index')->with('success', 'Équipement créé avec QR code généré.');
    }

    public function show(Equipement $equipement)
    {
        $equipement->load([
            'site',
            'attributions' => fn($q) => $q->with('ressource')->latest(),
            'interventions' => fn($q) => $q->with('site')->latest()->take(10),
            'stocks'        => fn($q) => $q->latest()->take(10),
        ]);

        $attribution_active = $equipement->attributions->firstWhere('statut', 'actif');

        return view('equipements.show', compact('equipement', 'attribution_active'));
    }

    public function edit(Equipement $equipement)
    {
        $sites = Site::orderBy('nom')->get();
        return view('equipements.edit', compact('equipement', 'sites'));
    }

    // public function update(Request $request, Equipement $equipement)
    // {
    //     $validated = $request->validate([
    //         'designation'      => 'required|string|max:255',
    //         'type'             => 'required|in:ordinateur,imprimante,serveur,switch,routeur,camera,alarme,autre',
    //         'marque'           => 'nullable|string|max:100',
    //         'modele'           => 'nullable|string|max:100',
    //         'numero_serie'     => 'nullable|string|max:100|unique:equipements,numero_serie,' . $equipement->id,
    //         'statut'           => 'required|in:disponible,attribue,en_reparation,hors_service',
    //         'site_id'          => 'required|exists:sites,id',
    //         'date_acquisition' => 'nullable|date',
    //         'valeur'           => 'nullable|numeric|min:0',
    //         'notes'            => 'nullable|string',
    //     ]);

    //     $equipement->update($validated);
    //     return redirect()->route('equipements.index')->with('success', 'Équipement mis à jour.');
    // }

    public function update(Request $request, Equipement $equipement)
    {
        $validated = $request->validate([
            'designation'      => 'required|string|max:255',
            'type'             => 'required|in:ordinateur,imprimante,serveur,switch,routeur,camera,alarme,autre',
            'marque'           => 'nullable|string|max:100',
            'modele'           => 'nullable|string|max:100',
            'numero_serie'     => 'nullable|string|max:100|unique:equipements,numero_serie,' . $equipement->id,
            'statut'           => 'required|in:disponible,attribue,en_reparation,hors_service',
            'site_id'          => 'required|exists:sites,id',
            'date_acquisition' => 'nullable|date',
            'valeur'           => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $equipement->update($validated);
        $equipement->genererQrCode(); // ✅ régénère le QR avec les infos à jour

        return redirect()->route('equipements.index')->with('success', 'Équipement mis à jour.');
    }

    public function destroy(Equipement $equipement)
    {
        if ($equipement->attributions()->where('statut', 'actif')->count() > 0) {
            return back()->with('error', 'Impossible : équipement actuellement attribué.');
        }

        // Supprimer le QR code
        if ($equipement->qr_code) {
            \Storage::disk('public')->delete($equipement->qr_code);
        }

        $equipement->delete();
        return redirect()->route('equipements.index')->with('success', 'Équipement supprimé.');
    }

    // Affiche le QR code seul (pour impression)
    public function qrCode(Equipement $equipement)
    {
        return view('equipements.qrcode', compact('equipement'));
    }

    // public function regenererQr(Equipement $equipement)
    // {
    //     if (!\Storage::disk('public')->exists('qrcodes')) {
    //         \Storage::disk('public')->makeDirectory('qrcodes');
    //     }

    //     $data = json_encode([
    //         'code'   => $equipement->code_inventaire,
    //         'type'   => $equipement->type,
    //         'marque' => $equipement->marque,
    //         'modele' => $equipement->modele,
    //         'serie'  => $equipement->numero_serie,
    //         'site'   => $equipement->site->nom ?? '',
    //         'statut' => $equipement->statut,
    //     ]);

    //     $filename = 'qrcodes/' . $equipement->code_inventaire . '.png';

    //     \QrCode::format('png')
    //         ->size(250)
    //         ->errorCorrection('H')
    //         ->generate($data, \Storage::disk('public')->path($filename));

    //     $equipement->update(['qr_code' => $filename]);

    //     return back()->with('success', 'QR code généré avec succès.');
    // }

    // public function regenererQr(Equipement $equipement)
    // {
    //     if (!\Storage::disk('public')->exists('qrcodes')) {
    //         \Storage::disk('public')->makeDirectory('qrcodes');
    //     }

    //     $data = json_encode([
    //         'code'   => $equipement->code_inventaire,
    //         'type'   => $equipement->type,
    //         'marque' => $equipement->marque,
    //         'modele' => $equipement->modele,
    //         'serie'  => $equipement->numero_serie,
    //         'site'   => $equipement->site->nom ?? '',
    //         'statut' => $equipement->statut,
    //     ]);

    //     $filename = 'qrcodes/' . $equipement->code_inventaire . '.svg';

    //     $svg = \QrCode::format('svg')
    //                 ->size(250)
    //                 ->errorCorrection('H')
    //                 ->generate($data);

    //     \Storage::disk('public')->put($filename, $svg);

    //     $equipement->update(['qr_code' => $filename]);

    //     return back()->with('success', 'QR code généré avec succès.');
    // }

    public function regenererQr(Equipement $equipement)
    {
        $equipement->genererQrCode();
        return back()->with('success', 'QR code régénéré avec succès.');
    }

    public function publicInfo($code)
    {
        $equipement = Equipement::where('code_inventaire', $code)
            ->with('site')
            ->firstOrFail();

        $attribution = $equipement->attributions()
            ->where('statut', 'actif')
            ->with('ressource')
            ->latest('date_attribution')
            ->first();

        return view('equipements.public-info', compact('equipement', 'attribution'));
    }
}