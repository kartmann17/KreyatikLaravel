<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\TimeLog;
use App\Models\Expense;
use App\Models\ContactMessage;

class DashboardController extends Controller
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
     * Affiche le tableau de bord adapté au rôle de l'utilisateur.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Statistiques des projets et clients
        $stats = [
            'projects' => Project::count(),
            'activeProjects' => Project::where('status', 'en-cours')->count(),
            'tasks' => Task::count(),
            'completedTasks' => Task::where('status', 'terminé')->count(),
            'clientCount' => Client::count(),
        ];
        
        // Statistiques financières (revenus et dépenses)
        $stats['monthlyEarnings'] = Project::where('status', 'terminé')
            ->whereMonth('updated_at', now()->month)
            ->sum('price');
            
        $stats['expenses'] = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        
        // Messages de contact récents et non lus
        $stats['recentMessages'] = ContactMessage::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $stats['unreadMessages'] = ContactMessage::unread()->count();
        
        // Projets récents
        $stats['recentProjects'] = Project::orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($project) {
                // Ajouter des propriétés pour l'affichage
                $project->completion_percentage = $this->calculateProjectCompletion($project);
                $project->status_label = $this->getStatusLabel($project->status);
                $project->status_color = $this->getStatusColor($project->status);
                return $project;
            });
        
        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Calcule le pourcentage de complétion d'un projet
     * 
     * @param Project $project
     * @return int
     */
    private function calculateProjectCompletion($project)
    {
        $totalTasks = $project->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        $completedTasks = $project->tasks()->where('status', 'terminé')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }
    
    /**
     * Retourne le libellé d'un statut
     * 
     * @param string $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'en-attente' => 'En attente',
            'en-cours' => 'En cours',
            'terminé' => 'Terminé',
            'annulé' => 'Annulé'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    /**
     * Retourne la couleur associée à un statut
     * 
     * @param string $status
     * @return string
     */
    private function getStatusColor($status)
    {
        $colors = [
            'en-attente' => 'warning',
            'en-cours' => 'primary',
            'terminé' => 'success',
            'annulé' => 'danger'
        ];
        
        return $colors[$status] ?? 'secondary';
    }
    
    /**
     * Retourne les données pour le graphique de revenus
     * 
     * @return array
     */
    private function getRevenueChartData()
    {
        $months = [];
        $data = [];
        
        // Récupérer les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('F');
            
            $monthlyRevenue = Project::where('status', 'terminé')
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->sum('price');
                
            $data[] = $monthlyRevenue;
        }
        
        return [
            'labels' => $months,
            'data' => $data,
        ];
    }
    
    /**
     * Affiche le tableau de bord des projets en cours avec timer.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projectsTimer()
    {
        $activeProjects = Project::with('client', 'tasks', 'timeLogs')
            ->where('status', 'en-cours')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $recentLogs = TimeLog::with('project', 'user')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.projects-timer', compact('activeProjects', 'recentLogs'));
    }
}