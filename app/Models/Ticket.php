<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'client_id',
        'project_id',
        'assigned_to',
        'created_by',
        'ticket_number',
        'browser',
        'os',
        'ip_address',
    ];

    /**
     * Les attributs à caster
     *
     * @var array
     */
    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Les relations à charger automatiquement
     *
     * @var array
     */
    protected $with = ['lastComment'];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Génération automatique du numéro de ticket lors de la création
        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TIK-' . date('Ym') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Récupère le client associé au ticket
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Récupère le projet associé au ticket
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Récupère l'utilisateur à qui le ticket est assigné
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Récupère l'utilisateur qui a créé le ticket
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Récupère tous les commentaires liés au ticket
     */
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Récupère le dernier commentaire du ticket
     */
    public function lastComment()
    {
        return $this->hasOne(TicketComment::class)->latest();
    }

    /**
     * Récupère uniquement les commentaires publics
     */
    public function publicComments()
    {
        return $this->hasMany(TicketComment::class)->where('is_private', false);
    }

    /**
     * Scope pour tickets ouverts
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'ouvert');
    }

    /**
     * Scope pour tickets en cours
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'en-cours');
    }

    /**
     * Scope pour tickets résolus
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'résolu');
    }

    /**
     * Scope pour tickets fermés
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'fermé');
    }

    /**
     * Scope pour tickets non fermés
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['fermé', 'résolu']);
    }

    /**
     * Scope pour tickets avec priorité haute
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['haute', 'urgente']);
    }
}
