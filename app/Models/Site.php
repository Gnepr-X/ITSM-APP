<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'nom', 'ville', 'adresse', 'responsable', 'telephone'
    ];

    public function ressources()    { return $this->hasMany(Ressource::class); }
    public function equipements()   { return $this->hasMany(Equipement::class); }
    public function attributions()  { return $this->hasMany(Attribution::class); }
    public function accesWifis()    { return $this->hasMany(AccesWifi::class); }
    public function cameras()       { return $this->hasMany(Camera::class); }
    public function alarmes()       { return $this->hasMany(Alarme::class); }
    public function interventions() { return $this->hasMany(Intervention::class); }
    public function stocks()        { return $this->hasMany(Stock::class); }
}