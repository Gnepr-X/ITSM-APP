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

    protected $casts = [
        'date_acquisition' => 'date',
    ];

    public function site()         { return $this->belongsTo(Site::class); }
    public function attributions() { return $this->hasMany(Attribution::class); }
    public function interventions(){ return $this->hasMany(Intervention::class); }
    public function stocks()       { return $this->hasMany(Stock::class); }

    // protected static function booted()
    // {
    //     static::created(function ($equip) {
    //         $equip->genererQrCode();
    //     });
    // }

    protected static function booted()
    {
        static::creating(function ($equip) {
            // Sécurité supplémentaire si le code_inventaire n'est pas déjà défini
            if (empty($equip->code_inventaire)) {
                $prefixe = strtoupper(substr($equip->type, 0, 3));
                $dernier = static::where('code_inventaire', 'like', "EQ-{$prefixe}-%")
                    ->orderByRaw('CAST(SUBSTRING_INDEX(code_inventaire, "-", -1) AS UNSIGNED) DESC')
                    ->value('code_inventaire');

                $prochainNumero = $dernier
                    ? (int) substr($dernier, strrpos($dernier, '-') + 1) + 1
                    : 1;

                $equip->code_inventaire = 'EQ-' . $prefixe . '-' . str_pad($prochainNumero, 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($equip) {
            $equip->genererQrCode();
        });
    }

    /**
     * Génère (ou régénère) le QR code avec les infos matériel + attribution active
     */
    public function genererQrCode(): void
    {
        if (!\Storage::disk('public')->exists('qrcodes')) {
            \Storage::disk('public')->makeDirectory('qrcodes');
        }

        // ✅ Change : on encode une URL au lieu du JSON
        $url = route('equipements.public-info', $this->code_inventaire);

        $filename = 'qrcodes/' . $this->code_inventaire . '.svg';

        $svg = \QrCode::format('svg')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate($url);

        \Storage::disk('public')->put($filename, $svg);

        $this->updateQuietly(['qr_code' => $filename]);
    }
}