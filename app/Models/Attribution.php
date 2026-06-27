<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attribution extends Model
{
    protected $fillable = [
        'numero_fiche', 'equipement_id', 'ressource_id', 'site_id',
        'date_attribution', 'attribue_par', 'observation', 'statut'
    ];

    protected $casts = [
        'date_attribution' => 'date',
    ];

    public function equipement()  { return $this->belongsTo(Equipement::class); }
    public function ressource()   { return $this->belongsTo(Ressource::class); }
    public function site()        { return $this->belongsTo(Site::class); }
    public function restitution() { return $this->hasOne(Restitution::class); }

    protected static function booted()
    {
        // Valeur temporaire obligatoire car numero_fiche est NOT NULL
        static::creating(function ($a) {
            $a->numero_fiche = 'TEMP';
        });

        // Génération du vrai numéro avec l'id disponible
        static::created(function ($a) {
            $a->updateQuietly([
                'numero_fiche' => 'ATT-'
                    . Carbon::parse($a->date_attribution)->format('Ymd')
                    . '-' . $a->id
            ]);

            // Mise à jour statut équipement
            $a->equipement->update(['statut' => 'attribue']);

            // Régénération du QR code avec les infos ressource
            $a->equipement->genererQrCode();
        });
    }
}