<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeLog;

class StatsController extends Controller
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
     * Affiche les statistiques détaillées.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupération des données réelles pour les statistiques
        
        // Revenus mensuels (12 derniers mois)
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        // Revenus par source (utilisation du champ description des projets comme source)
        $revenueBySource = $this->getRevenueBySource();
        
        // Tâches par statut
        $tasksByStatus = $this->getTasksByStatus();
        
        // Temps passé par projet (top 5)
        $timeByProject = $this->getTimeByProject();
        
        return view('admin.stats.index', compact(
            'monthlyRevenue',
            'revenueBySource',
            'tasksByStatus',
            'timeByProject'
        ));
    }

    /**
     * Récupère les revenus mensuels des 12 derniers mois
     *
     * @return array
     */
    private function getMonthlyRevenue()
    {
        $labels = [];
        $data = [];
        
        // Récupérer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('F Y');
            
            $monthlyRevenue = Project::where('status', 'terminé')
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->sum('price');
                
            $data[] = $monthlyRevenue;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Récupère les revenus par source (basé sur le champ client_id des projets)
     *
     * @return array
     */
    private function getRevenueBySource()
    {
        $projects = Project::with('client')
            ->where('status', 'terminé')
            ->get();
            
        $revenueByClient = collect();
        
        foreach ($projects as $project) {
            $clientName = $project->client ? $project->client->name : 'Sans client';
            
            if (!$revenueByClient->has($clientName)) {
                $revenueByClient[$clientName] = 0;
            }
            
            $revenueByClient[$clientName] += $project->price;
        }
        
        // Trier par montant décroissant et prendre les 5 premiers
        $revenueByClient = $revenueByClient->sortDesc()->take(5);
        
        return [
            'labels' => $revenueByClient->keys()->toArray(),
            'data' => $revenueByClient->values()->toArray(),
        ];
    }

    /**
     * Récupère le nombre de tâches par statut
     *
     * @return array
     */
    private function getTasksByStatus()
    {
        $tasksByStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        $labels = [];
        $data = [];
        
        $statusLabels = [
            'a-faire' => 'À faire',
            'en-cours' => 'En cours',
            'a-tester' => 'À tester',
            'termine' => 'Terminées'
        ];
        
        foreach ($tasksByStatus as $task) {
            $statusLabel = isset($statusLabels[$task->status]) ? $statusLabels[$task->status] : $task->status;
            $labels[] = $statusLabel;
            $data[] = $task->count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Récupère le temps passé sur les 5 projets avec le plus de temps
     *
     * @return array
     */
    private function getTimeByProject()
    {
        $timeLogs = TimeLog::with('project')
            ->select('project_id', DB::raw('SUM(duration) as total_duration'))
            ->groupBy('project_id')
            ->orderBy('total_duration', 'desc')
            ->limit(5)
            ->get();
            
        $labels = [];
        $data = [];
        
        foreach ($timeLogs as $log) {
            if ($log->project) {
                $labels[] = $log->project->title;
                // Convertir les secondes en heures
                $data[] = round($log->total_duration / 3600, 1);
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * Génère un rapport de statistiques personnalisé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function generateReport(Request $request)
    {
        // Validation des données
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:revenue,tasks,time',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $reportType = $request->type;
        
        // Récupération des données réelles pour le rapport
        switch ($reportType) {
            case 'revenue':
                $reportData = $this->generateRevenueReport($startDate, $endDate);
                break;
            case 'tasks':
                $reportData = $this->generateTasksReport($startDate, $endDate);
                break;
            case 'time':
                $reportData = $this->generateTimeReport($startDate, $endDate);
                break;
            default:
                $reportData = [];
        }
        
        return view('admin.stats.report', compact('reportData', 'reportType', 'startDate', 'endDate'));
    }
    
    /**
     * Génère un rapport sur les revenus dans une période donnée
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function generateRevenueReport($startDate, $endDate)
    {
        $projects = Project::whereBetween('updated_at', [$startDate, $endDate])
            ->where('status', 'terminé')
            ->with('client')
            ->get();
            
        $totalRevenue = $projects->sum('price');
        $projectsCount = $projects->count();
        
        $revenueByClient = [];
        foreach ($projects as $project) {
            $clientName = $project->client ? $project->client->name : 'Sans client';
            
            if (!isset($revenueByClient[$clientName])) {
                $revenueByClient[$clientName] = 0;
            }
            
            $revenueByClient[$clientName] += $project->price;
        }
        
        // Trier par montant décroissant
        arsort($revenueByClient);
        
        return [
            'totalRevenue' => $totalRevenue,
            'projectsCount' => $projectsCount,
            'revenueByClient' => $revenueByClient,
            'projects' => $projects
        ];
    }
    
    /**
     * Génère un rapport sur les tâches dans une période donnée
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function generateTasksReport($startDate, $endDate)
    {
        $tasks = Task::whereBetween('created_at', [$startDate, $endDate])
            ->with('project')
            ->get();
            
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'termine')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $tasksByStatus = [];
        foreach ($tasks as $task) {
            if (!isset($tasksByStatus[$task->status])) {
                $tasksByStatus[$task->status] = 0;
            }
            
            $tasksByStatus[$task->status]++;
        }
        
        $tasksByProject = [];
        foreach ($tasks as $task) {
            $projectName = $task->project ? $task->project->title : 'Sans projet';
            
            if (!isset($tasksByProject[$projectName])) {
                $tasksByProject[$projectName] = 0;
            }
            
            $tasksByProject[$projectName]++;
        }
        
        // Trier par nombre de tâches décroissant
        arsort($tasksByProject);
        
        return [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'completionRate' => $completionRate,
            'tasksByStatus' => $tasksByStatus,
            'tasksByProject' => $tasksByProject,
            'tasks' => $tasks
        ];
    }
    
    /**
     * Génère un rapport sur le temps passé dans une période donnée
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function generateTimeReport($startDate, $endDate)
    {
        $timeLogs = TimeLog::whereBetween('created_at', [$startDate, $endDate])
            ->with(['project', 'task', 'user'])
            ->get();
            
        $totalDuration = $timeLogs->sum('duration');
        $totalHours = round($totalDuration / 3600, 1);
        $logsCount = $timeLogs->count();
        
        $timeByProject = [];
        foreach ($timeLogs as $log) {
            $projectName = $log->project ? $log->project->title : 'Sans projet';
            
            if (!isset($timeByProject[$projectName])) {
                $timeByProject[$projectName] = 0;
            }
            
            $timeByProject[$projectName] += $log->duration;
        }
        
        // Convertir les secondes en heures et trier par durée décroissante
        foreach ($timeByProject as &$duration) {
            $duration = round($duration / 3600, 1);
        }
        arsort($timeByProject);
        
        $timeByUser = [];
        foreach ($timeLogs as $log) {
            $userName = $log->user ? $log->user->name : 'Utilisateur inconnu';
            
            if (!isset($timeByUser[$userName])) {
                $timeByUser[$userName] = 0;
            }
            
            $timeByUser[$userName] += $log->duration;
        }
        
        // Convertir les secondes en heures et trier par durée décroissante
        foreach ($timeByUser as &$duration) {
            $duration = round($duration / 3600, 1);
        }
        arsort($timeByUser);
        
        return [
            'totalDuration' => $totalDuration,
            'totalHours' => $totalHours,
            'logsCount' => $logsCount,
            'timeByProject' => $timeByProject,
            'timeByUser' => $timeByUser,
            'timeLogs' => $timeLogs
        ];
    }
} 