<?php

namespace App\Observers;

use App\Models\TimeLog;
use App\Models\Project;

class TimeLogObserver
{
    /**
     * Handle the TimeLog "created" event.
     */
    public function created(TimeLog $timeLog): void
    {
        // Mettre à jour le temps total passé sur le projet
        $project = Project::find($timeLog->project_id);
        if ($project) {
            $project->total_time_spent += $timeLog->duration;
            $project->save();
        }
    }

    /**
     * Handle the TimeLog "updated" event.
     */
    public function updated(TimeLog $timeLog): void
    {
        //
    }

    /**
     * Handle the TimeLog "deleted" event.
     */
    public function deleted(TimeLog $timeLog): void
    {
        // Mettre à jour le temps total passé sur le projet
        $project = Project::find($timeLog->project_id);
        if ($project) {
            $project->total_time_spent -= $timeLog->duration;
            if ($project->total_time_spent < 0) {
                $project->total_time_spent = 0;
            }
            $project->save();
        }
    }

    /**
     * Handle the TimeLog "restored" event.
     */
    public function restored(TimeLog $timeLog): void
    {
        //
    }

    /**
     * Handle the TimeLog "force deleted" event.
     */
    public function forceDeleted(TimeLog $timeLog): void
    {
        //
    }
}
