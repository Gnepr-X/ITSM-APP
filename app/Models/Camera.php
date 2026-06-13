<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $fillable = [
        'site_id', 'reference', 'marque', 'modele',
        'emplacement', 'adresse_ip', 'date_installation', 'statut'
    ];

    protected $casts = [
        'date_installation' => 'date',
    ];

    public function site() { return $this->belongsTo(Site::class); }

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'equipement_id')
                    ->whereHas('equipement', fn($q) => $q->where('type', 'camera'));
    }

    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'actif'    => 'success',
            'inactif'  => 'secondary',
            'en_panne' => 'danger',
            default    => 'secondary',
        };
    }
}