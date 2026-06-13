<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesWifi extends Model
{
    protected $table = 'acces_wifis';

    protected $fillable = [
        'site_id', 'operateur', 'nom_box', 'ssid',
        'mot_de_passe', 'adresse_ip', 'adresse_mac',
        'date_installation', 'statut', 'notes'
    ];

    protected $casts = [
        'date_installation' => 'date',
        // Le mot de passe est visible uniquement aux admins
        // Gérez la visibilité dans le controller/vue
    ];

    public function site() { return $this->belongsTo(Site::class); }

    public function getOperateurBadgeAttribute()
    {
        return match($this->operateur) {
            'ORANGE' => 'bg-warning text-dark',
            'MTN'    => 'bg-warning text-dark',
            default  => 'bg-secondary',
        };
    }
}
