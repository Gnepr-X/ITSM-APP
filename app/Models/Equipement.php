<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    protected $fillable = [
        'code_inventaire', 'designation', 'type', 'marque', 'modele',
        'numero_serie', 'statut', 'site_id', 'date_acquisition',
        'valeur', 'qr_code', 'notes'
    ];

    // ✅ Ajouter les casts
    protected $casts = [
        'date_acquisition' => 'date',
    ];

    public function site()         { return $this->belongsTo(Site::class); }
    public function attributions() { return $this->hasMany(Attribution::class); }
    public function interventions(){ return $this->hasMany(Intervention::class); }
    public function stocks()       { return $this->hasMany(Stock::class); }

    protected static function booted()
    {
        static::created(function ($equip) {
            if (!\Storage::disk('public')->exists('qrcodes')) {
                \Storage::disk('public')->makeDirectory('qrcodes');
            }

            $data = json_encode([
                'code'   => $equip->code_inventaire,
                'type'   => $equip->type,
                'marque' => $equip->marque,
                'modele' => $equip->modele,
                'serie'  => $equip->numero_serie,
                'site'   => $equip->site->nom ?? '',
                'statut' => $equip->statut,
            ]);

            $filename = 'qrcodes/' . $equip->code_inventaire . '.png';

            \QrCode::format('png')
                   ->size(250)
                   ->errorCorrection('H')
                   ->generate($data, \Storage::disk('public')->path($filename));

            $equip->updateQuietly(['qr_code' => $filename]);
        });
    }
}