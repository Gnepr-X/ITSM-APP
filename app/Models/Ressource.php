<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'matricule', 'poste',
        'departement', 'site_id', 'email', 'telephone'
    ];

    public function site()         { return $this->belongsTo(Site::class); }
    public function attributions() { return $this->hasMany(Attribution::class); }
    public function interventions(){ return $this->hasMany(Intervention::class); }

    // Équipements actuellement attribués
    public function equipementsActifs()
    {
        return $this->hasManyThrough(Equipement::class, Attribution::class, 'ressource_id', 'id', 'id', 'equipement_id')
                    ->where('attributions.statut', 'actif');
    }

    public function getNomCompletAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
}