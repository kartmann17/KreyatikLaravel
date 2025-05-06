<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Ticket;

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
     * Affiche le tableau de bord client.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $clientId = $user->client_id;
        
        // Statistiques des projets
        $projects = Project::where('client_id', $clientId)->get();
        $activeProjects = $projects->where('status', 'en-cours')->count();
        
        // Statistiques des tickets
        $tickets = Ticket::where('client_id', $clientId)->get();
        $openTickets = $tickets->whereIn('status', ['ouvert', 'en-cours'])->count();
        
        // Données pour le tableau de bord
        $stats = [
            'projectCount' => $projects->count(),
            'activeProjects' => $activeProjects,
            'ticketCount' => $tickets->count(),
            'openTickets' => $openTickets,
        ];
        
        // Projets récents
        $recentProjects = Project::where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Tickets récents
        $recentTickets = Ticket::where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('client.dashboard', compact('stats', 'recentProjects', 'recentTickets'));
    }
} 