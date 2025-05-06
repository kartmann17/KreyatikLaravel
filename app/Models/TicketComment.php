<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
        'is_private',
        'is_solution',
        'attachment',
    ];

    /**
     * Les attributs à caster
     *
     * @var array
     */
    protected $casts = [
        'is_private' => 'boolean',
        'is_solution' => 'boolean',
    ];

    /**
     * Récupère le ticket associé au commentaire
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Récupère l'utilisateur qui a créé le commentaire
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Détermine si le commentaire a une pièce jointe
     *
     * @return bool
     */
    public function hasAttachment()
    {
        return !empty($this->attachment);
    }

    /**
     * Scope pour commentaires publics
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope pour commentaires privés
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope pour solutions
     */
    public function scopeSolutions($query)
    {
        return $query->where('is_solution', true);
    }
}
