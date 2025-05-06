<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Project extends Model
{
    use HasFactory;
    use HasSEO;

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'client_id',
        'user_id',
        'start_date',
        'end_date',
        'budget',
    ];

    /**
     * Les attributs qui doivent être convertis
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'float',
    ];

    /**
     * Récupère les tâches associées à ce projet
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Récupère les entrées de temps associées à ce projet
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Récupère l'utilisateur (responsable) associé à ce projet
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère le client associé à ce projet
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Calcule la durée totale passée sur ce projet
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
     * Calcule le pourcentage d'avancement global du projet
     */
    public function getProgressAttribute()
    {
        $tasks = $this->tasks;
        
        if ($tasks->isEmpty()) {
            return 0;
        }
        
        $totalProgress = $tasks->sum('progress');
        return round($totalProgress / $tasks->count());
    }
    
    /**
     * Formatte le temps total passé au format heures:minutes
     */
    public function getFormattedTotalTimeAttribute()
    {
        $hours = floor($this->total_time_spent / 3600);
        $minutes = floor(($this->total_time_spent % 3600) / 60);
        
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Génère les métadonnées SEO pour ce projet
     */
    public function getDynamicSEOData(): SEOData
    {
        // Construire une description plus détaillée
        $description = $this->description;
        if (!$description) {
            $description = "Projet {$this->name} " . 
                ($this->client ? "pour {$this->client->name}. " : ". ") .
                "Statut: {$this->status}";
        }

        // Construire un titre enrichi
        $title = "{$this->name}";
        if ($this->client) {
            $title .= " | {$this->client->name}";
        }

        return new SEOData(
            title: $title,
            description: $description,
            author: $this->user ? $this->user->name : null,
            image: null, // Si vous avez des images de projet, vous pouvez les ajouter ici
            url: route('admin.projects.show', $this->id),
            enableTitleSuffix: true,
        );
    }
}
