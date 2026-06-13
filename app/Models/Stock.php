<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'equipement_id', 'mouvement', 'quantite',
        'motif', 'site_id', 'effectue_par',
        'date_mouvement', 'notes'
    ];

    protected $casts = [
        'date_mouvement' => 'date',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }
    public function site()       { return $this->belongsTo(Site::class); }

    public function getMouvementBadgeAttribute()
    {
        return match($this->mouvement) {
            'entree' => 'success',
            'sortie' => 'danger',
            default  => 'secondary',
        };
    }

    // Scope pour filtrer les entrées/sorties
    public function scopeEntrees($query) { return $query->where('mouvement', 'entree'); }
    public function scopeSorties($query) { return $query->where('mouvement', 'sortie'); }
}