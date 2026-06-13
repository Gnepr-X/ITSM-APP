<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        static::creating(function ($a) {
            $a->numero_fiche = 'ATT-' . date('Ymd') . '-' . str_pad(
                Attribution::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );
        });

        // Mettre à jour le statut de l'équipement à l'attribution
        static::created(function ($a) {
            $a->equipement->update(['statut' => 'attribue']);
        });
    }
}