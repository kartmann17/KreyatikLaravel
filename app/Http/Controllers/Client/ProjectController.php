<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Constructeur du contrôleur
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    /**
     * Affiche la liste des projets du client connecté
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupérer l'utilisateur et son client associé
        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Votre compte n\'est pas associé à un client');
        }

        // Récupérer les projets associés à ce client
        $projects = Project::where('client_id', $client->id)
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('client.projects.index', compact('projects'));
    }

    /**
     * Affiche les détails d'un projet spécifique
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        // Récupérer l'utilisateur et son client associé
        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Votre compte n\'est pas associé à un client');
        }

        // Récupérer le projet avec vérification qu'il appartient bien au client
        $project = Project::where('id', $id)
                         ->where('client_id', $client->id)
                         ->firstOrFail();

        // Récupérer les tâches associées au projet
        $tasks = Task::where('project_id', $project->id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        // Calculer le pourcentage d'avancement du projet
        $completedTasks = $tasks->where('status', 'terminé')->count();
        $totalTasks = $tasks->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('client.projects.show', compact('project', 'tasks', 'progress'));
    }
} 