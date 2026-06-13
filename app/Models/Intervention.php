<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $fillable = [
        'numero_ticket', 'site_id', 'ressource_id', 'equipement_id',
        'type', 'titre', 'description', 'priorite', 'statut',
        'date_ouverture', 'date_resolution', 'solution'
    ];

    protected $casts = [
        'date_ouverture'   => 'date',
        'date_resolution'  => 'date',
    ];

    public function site()       { return $this->belongsTo(Site::class); }
    public function ressource()  { return $this->belongsTo(Ressource::class); }
    public function equipement() { return $this->belongsTo(Equipement::class); }

    public function getDureeAttribute()
    {
        if (!$this->date_resolution) return null;
        return $this->date_ouverture->diffInDays($this->date_resolution) . ' j';
    }

    public function getPrioriteBadgeAttribute()
    {
        return match($this->priorite) {
            'critique' => 'danger',
            'haute'    => 'warning',
            'normale'  => 'primary',
            'basse'    => 'secondary',
            default    => 'secondary',
        };
    }

    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'ouvert'   => 'danger',
            'en_cours' => 'warning',
            'resolu'   => 'success',
            'ferme'    => 'secondary',
            default    => 'secondary',
        };
    }

    protected static function booted()
    {
        static::creating(function ($i) {
            $i->numero_ticket = 'TKT-' . date('Ymd') . '-' . str_pad(
                Intervention::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );
        });

        // Date de résolution automatique quand statut = résolu
        static::updating(function ($i) {
            if ($i->isDirty('statut') && $i->statut === 'resolu' && !$i->date_resolution) {
                $i->date_resolution = today();
            }
        });
    }
}