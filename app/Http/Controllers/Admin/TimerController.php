<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
    /**
     * Affiche la vue du chronomètre
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::with('tasks')->orderBy('title')->get();
        $recentLogs = TimeLog::with(['project', 'task'])
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();
        
        return view('admin.timer', compact('projects', 'recentLogs'));
    }
    
    /**
     * Enregistre un time log
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);
        
        $timeLog = new TimeLog();
        $timeLog->project_id = $request->project_id;
        $timeLog->task_id = $request->task_id;
        $timeLog->user_id = Auth::id();
        $timeLog->description = $request->description;
        $timeLog->duration = $request->duration;
        $timeLog->started_at = Carbon::now()->subSeconds($request->duration);
        $timeLog->ended_at = Carbon::now();
        $timeLog->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Temps enregistré avec succès',
            'timeLog' => $timeLog->load(['project', 'task'])
        ]);
    }
    
    /**
     * Endpoint appelé par la vue timer pour enregistrer un log de temps
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logTime(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);
        
        $timeLog = new TimeLog();
        $timeLog->project_id = $request->project_id;
        $timeLog->task_id = $request->task_id ?: null;
        $timeLog->user_id = Auth::id();
        $timeLog->description = $request->description ?: 'Temps enregistré via le chronomètre';
        $timeLog->duration = $request->duration;
        $timeLog->started_at = Carbon::now()->subSeconds($request->duration);
        $timeLog->ended_at = Carbon::now();
        $timeLog->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Temps enregistré avec succès',
            'timeLog' => $timeLog
        ]);
    }
    
    /**
     * Récupère les logs de temps par période
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $period = $request->period ?? 'today';
        $query = TimeLog::with(['project', 'task'])
                       ->where('user_id', Auth::id());
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                break;
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        // Calcul des statistiques
        $totalDuration = $logs->sum('duration');
        $projectStats = $logs->groupBy('project_id')
                            ->map(function ($items, $key) {
                                $project = $items->first()->project;
                                return [
                                    'id' => $key,
                                    'name' => $project->title,
                                    'duration' => $items->sum('duration'),
                                ];
                            })->values();
                            
        $dailyStats = $logs->groupBy(function ($item) {
                            return $item->created_at->format('Y-m-d');
                        })
                        ->map(function ($items, $key) {
                            return [
                                'date' => $key,
                                'duration' => $items->sum('duration'),
                            ];
                        })->values();
        
        return response()->json([
            'success' => true,
            'logs' => $logs,
            'stats' => [
                'totalDuration' => $totalDuration,
                'projectStats' => $projectStats,
                'dailyStats' => $dailyStats
            ]
        ]);
    }
    
    /**
     * Supprime un enregistrement de temps
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Récupération du log
            $timeLog = TimeLog::findOrFail($id);
            
            // Vérification des permissions (l'utilisateur ne peut supprimer que ses propres logs)
            if ($timeLog->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à supprimer cet enregistrement.'
                ], 403);
            }

            // Suppression
            $timeLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Enregistrement de temps supprimé avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour un enregistrement de temps existant
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'duration' => 'required|integer|min:1',
                'description' => 'nullable|string|max:500',
            ]);

            // Récupération du log
            $timeLog = TimeLog::findOrFail($id);
            
            // Vérification des permissions (l'utilisateur ne peut modifier que ses propres logs)
            if ($timeLog->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier cet enregistrement.'
                ], 403);
            }

            // Mise à jour des données
            $timeLog->duration = $validated['duration'];
            $timeLog->description = $validated['description'];
            $timeLog->save();

            return response()->json([
                'success' => true,
                'message' => 'Enregistrement de temps mis à jour avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }
} 