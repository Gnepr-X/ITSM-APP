<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alarme extends Model
{
    protected $fillable = [
        'site_id', 'type_systeme', 'marque', 'zone_couverte',
        'code_acces', 'date_installation', 'statut'
    ];

    protected $casts = [
        'date_installation' => 'date',
    ];

    // Ne jamais exposer le code d'accès dans les API/JSON
    protected $hidden = ['code_acces'];

    public function site() { return $this->belongsTo(Site::class); }

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