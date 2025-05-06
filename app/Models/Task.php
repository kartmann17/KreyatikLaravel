<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'project_id',
        'user_id',
        'due_date',
        'estimated_hours',
    ];

    /**
     * Les attributs qui doivent être convertis
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'date',
        'estimated_hours' => 'float',
    ];

    protected $appends = ['status_label'];

    /**
     * Les statuts possibles pour une tâche
     */
    const STATUS_TODO = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_REVIEW = 'review';
    const STATUS_DONE = 'done';

    /**
     * Les priorités possibles pour une tâche
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Récupère le projet associé à cette tâche
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Récupère l'utilisateur assigné à cette tâche
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère les entrées de temps associées à cette tâche
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Calcule la durée totale passée sur cette tâche
     *
     * @return int
     */
    public function getTotalDuration()
    {
        return $this->timeLogs()->sum('duration');
    }

    /**
     * Obtient la durée totale formatée
     *
     * @return string
     */
    public function getFormattedTotalDurationAttribute()
    {
        $totalMinutes = $this->getTotalDuration();
        $minutes = $totalMinutes % 60;
        $hours = floor($totalMinutes / 60);
        
        if ($hours > 0) {
            return $hours . 'h ' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
        }
        
        return $minutes . 'm';
    }

    /**
     * Met à jour automatiquement le pourcentage d'avancement selon le statut
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        
        // Mise à jour automatique du pourcentage selon le statut
        if ($value == 'a-faire') {
            $this->attributes['progress'] = 0;
        } elseif ($value == 'termine') {
            $this->attributes['progress'] = 100;
        }
    }
    
    /**
     * Obtient le libellé du statut
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'a-faire' => 'À faire',
            'en-cours' => 'En cours',
            'a-tester' => 'À tester',
            'termine' => 'Terminé'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Vérifie si la tâche est en retard
     *
     * @return boolean
     */
    public function isOverdue()
    {
        if (!$this->due_date || $this->status === self::STATUS_DONE) {
            return false;
        }
        
        return $this->due_date->isPast();
    }

    /**
     * Vérifie si la tâche est terminée
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_DONE;
    }
}
