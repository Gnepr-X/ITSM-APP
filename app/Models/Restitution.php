<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restitution extends Model
{
    protected $fillable = [
        'attribution_id', 'date_restitution',
        'recu_par', 'etat_retour', 'observation'
    ];

    protected $casts = [
        'date_restitution' => 'date',
    ];

    public function attribution() { return $this->belongsTo(Attribution::class); }

    // Accès direct à l'équipement via attribution
    public function equipement()
    {
        return $this->hasOneThrough(
            Equipement::class, Attribution::class,
            'id', 'id', 'attribution_id', 'equipement_id'
        );
    }

    protected static function booted()
    {
        static::created(function ($r) {
            $r->attribution->update(['statut' => 'restitue']);

            $statut = in_array($r->etat_retour, ['hors_service'])
                ? 'hors_service'
                : ($r->etat_retour === 'endommagé' ? 'en_reparation' : 'disponible');

            $r->attribution->equipement->update(['statut' => $statut]);
            $r->attribution->equipement->genererQrCode(); // ✅ régénère le QR sans ressource
        });
    }
}