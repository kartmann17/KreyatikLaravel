<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeLog extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'description',
        'started_at',
        'ended_at',
        'duration',
    ];

    /**
     * Les attributs qui doivent être convertis
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration' => 'integer', // durée en secondes
    ];

    /**
     * Obtient l'utilisateur associé à cette entrée de temps
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtient le projet associé à cette entrée de temps
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Obtient la tâche associée à cette entrée de temps
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Définit la méthode boot pour définir les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();
        
        // Calcule automatiquement la durée si started_at et ended_at sont définis
        static::saving(function ($timeLog) {
            if ($timeLog->started_at && $timeLog->ended_at) {
                $timeLog->duration = $timeLog->calculateDuration();
            }
        });
    }
    
    /**
     * Calcule la durée entre started_at et ended_at en secondes
     *
     * @return int
     */
    public function calculateDuration()
    {
        if (!$this->started_at || !$this->ended_at) {
            return 0;
        }
        
        return $this->started_at->diffInSeconds($this->ended_at);
    }
    
    /**
     * Obtient la durée formatée
     *
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration % 60;
        $hours = floor($this->duration / 60);
        
        if ($hours > 0) {
            return $hours . 'h ' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
        }
        
        return $minutes . 'm';
    }
    
    /**
     * Définit la date de début formatée
     *
     * @return string
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->started_at ? $this->started_at->format('d/m/Y H:i') : '';
    }
    
    /**
     * Définit la date de fin formatée
     *
     * @return string
     */
    public function getFormattedEndTimeAttribute()
    {
        return $this->ended_at ? $this->ended_at->format('d/m/Y H:i') : '';
    }
    
    /**
     * Scope pour filtrer les entrées par utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope pour filtrer les entrées par projet
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }
    
    /**
     * Scope pour filtrer les entrées par tâche
     */
    public function scopeForTask($query, $taskId)
    {
        return $query->where('task_id', $taskId);
    }
    
    /**
     * Scope pour filtrer les entrées par date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('started_at', $date);
    }
    
    /**
     * Scope pour filtrer les entrées par période
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereDate('started_at', '>=', $startDate)
                     ->whereDate('started_at', '<=', $endDate);
    }
}
