<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\TimeLog;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la liste des projets.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupérer les clients pour le formulaire d'ajout
        $clients = Client::all();
        
        // Récupérer les projets avec leur client
        $projects = Project::with('client')->get();
        
        return view('admin.projects.index', compact('projects', 'clients'));
    }
    
    /**
     * Affiche la page du timer pour suivre le temps passé sur les projets.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function timer()
    {
        // Récupérer les projets avec leurs tâches pour le timer
        $projects = Project::with('tasks')->get();
        
        // Récupérer les logs de temps récents
        $timeLogs = TimeLog::with(['project', 'task'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.timer', compact('projects', 'timeLogs'));
    }
    
    /**
     * Affiche les détails d'un projet
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::with(['client', 'tasks'])->find($id);
        
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Projet non trouvé'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }
    
    /**
     * Récupère les données d'un projet pour l'édition
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Projet non trouvé'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }
    
    /**
     * Ajoute un nouveau projet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:en-cours,terminé,en-attente',
        ]);
        
        // Créer le projet
        $project = new Project();
        $project->title = $validated['title'];
        $project->client_id = $validated['client_id'] ?? null;
        $project->description = $validated['description'] ?? null;
        $project->price = $validated['price'] ?? 0;
        $project->status = $validated['status'];
        $project->total_time_spent = 0; // Initialiser à 0
        $project->save();
        
        // Si la requête attend une réponse JSON (AJAX)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet ajouté avec succès',
                'project' => $project
            ]);
        }
        
        // Sinon, rediriger avec un message flash
        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet ajouté avec succès');
    }
    
    /**
     * Met à jour un projet existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:en-cours,terminé,en-attente',
        ]);
        
        // Récupérer et mettre à jour le projet
        $project = Project::find($id);
        
        if (!$project) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Projet non trouvé'
                ], 404);
            }
            
            return redirect()->route('admin.projects.index')
                ->with('error', 'Projet non trouvé');
        }
        
        // Mettre à jour les propriétés
        $project->title = $validated['title'];
        $project->client_id = $validated['client_id'] ?? null;
        $project->description = $validated['description'] ?? null;
        $project->price = $validated['price'] ?? 0;
        $project->status = $validated['status'];
        $project->save();
        
        // Si la requête attend une réponse JSON (AJAX)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet mis à jour avec succès',
                'project' => $project
            ]);
        }
        
        // Sinon, rediriger avec un message flash
        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet mis à jour avec succès');
    }
    
    /**
     * Met à jour le statut d'un projet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'status' => 'required|string|in:en-cours,terminé,en-attente',
        ]);
        
        // Récupérer et mettre à jour le projet
        $project = Project::find($id);
        
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Projet non trouvé'
            ], 404);
        }
        
        // Mettre à jour le statut
        $project->status = $request->status;
        $project->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Statut du projet mis à jour',
            'project' => $project
        ]);
    }
    
    /**
     * Supprime un projet.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        
        try {
            // Vérifier si le projet a des tâches
            if ($project->tasks()->count() > 0) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet contient des tâches et ne peut pas être supprimé. Supprimez d\'abord les tâches.'
                    ], 400);
                }
                
                return redirect()->route('admin.projects.index')
                    ->with('error', 'Ce projet contient des tâches et ne peut pas être supprimé. Supprimez d\'abord les tâches.');
            }
            
            // Supprimer les logs de temps associés
            $project->timeLogs()->delete();
            
            // Supprimer le projet
            $project->delete();
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet supprimé avec succès'
                ]);
            }
            
            return redirect()->route('admin.projects.index')
                ->with('success', 'Projet supprimé avec succès');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.projects.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
    
    /**
     * Enregistre le temps passé sur un projet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logTime(Request $request)
    {
        // Validation
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
        
        // Créer un nouvel enregistrement de temps
        $timeLog = new TimeLog();
        $timeLog->user_id = auth()->id();
        $timeLog->project_id = $request->project_id;
        $timeLog->task_id = $request->task_id;
        $timeLog->duration = $request->duration;
        $timeLog->description = $request->description ?: 'Temps enregistré';
        $timeLog->started_at = now()->subSeconds($request->duration);
        $timeLog->ended_at = now();
        $timeLog->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Temps enregistré avec succès',
            'time_log' => $timeLog
        ]);
    }
} 