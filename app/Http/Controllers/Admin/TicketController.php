<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Affiche la liste des tickets.
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';
        $priority = $request->priority ?? 'all';
        
        $query = Ticket::with(['client', 'project', 'assignedUser', 'creator']);
        
        // Filtrer par statut si nécessaire
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filtrer par priorité si nécessaire
        if ($priority !== 'all') {
            $query->where('priority', $priority);
        }
        
        // Recherche par titre ou numéro de ticket
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistiques
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::open()->count(),
            'in_progress' => Ticket::inProgress()->count(),
            'resolved' => Ticket::resolved()->count(),
            'closed' => Ticket::closed()->count(),
            'high_priority' => Ticket::highPriority()->count(),
        ];
        
        return view('admin.tickets.index', compact('tickets', 'stats', 'status', 'priority'));
    }

    /**
     * Affiche le formulaire de création d'un ticket.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('title')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'staff')->get();
        
        return view('admin.tickets.create', compact('clients', 'projects', 'users'));
    }

    /**
     * Enregistre un nouveau ticket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:basse,moyenne,haute,urgente',
            'status' => 'required|in:ouvert,en-cours,résolu,fermé',
        ]);
        
        $ticket = new Ticket();
        $ticket->title = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->client_id = $validated['client_id'];
        $ticket->project_id = $validated['project_id'];
        $ticket->assigned_to = $validated['assigned_to'];
        $ticket->priority = $validated['priority'];
        $ticket->status = $validated['status'];
        $ticket->created_by = Auth::id();
        $ticket->browser = $request->header('User-Agent');
        $ticket->ip_address = $request->ip();
        $ticket->save();
        
        // Ajouter un commentaire initial si un message est fourni
        if ($request->has('comment') && !empty($request->comment)) {
            $ticket->comments()->create([
                'user_id' => Auth::id(),
                'content' => $request->comment,
                'is_private' => $request->has('is_private') ? true : false,
            ]);
        }
        
        return redirect()->route('admin.tickets.show', $ticket->id)
                        ->with('success', 'Ticket créé avec succès.');
    }

    /**
     * Affiche les détails d'un ticket.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['client', 'project', 'assignedUser', 'creator', 'comments.user'])
                      ->findOrFail($id);
        
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('title')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'staff')->get();
        
        return view('admin.tickets.show', compact('ticket', 'clients', 'projects', 'users'));
    }

    /**
     * Affiche le formulaire d'édition d'un ticket.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('title')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'staff')->get();
        
        return view('admin.tickets.edit', compact('ticket', 'clients', 'projects', 'users'));
    }

    /**
     * Met à jour un ticket.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:basse,moyenne,haute,urgente',
            'status' => 'required|in:ouvert,en-cours,résolu,fermé',
        ]);
        
        $ticket = Ticket::findOrFail($id);
        
        // Vérifier si le statut a changé
        $statusChanged = $ticket->status !== $validated['status'];
        
        // Mise à jour des champs
        $ticket->title = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->client_id = $validated['client_id'];
        $ticket->project_id = $validated['project_id'];
        $ticket->assigned_to = $validated['assigned_to'];
        $ticket->priority = $validated['priority'];
        $ticket->status = $validated['status'];
        
        // Mise à jour des timestamps de résolution ou fermeture
        if ($statusChanged) {
            if ($validated['status'] === 'résolu' && !$ticket->resolved_at) {
                $ticket->resolved_at = Carbon::now();
            } elseif ($validated['status'] === 'fermé' && !$ticket->closed_at) {
                $ticket->closed_at = Carbon::now();
            }
        }
        
        $ticket->save();
        
        // Ajouter un commentaire si fourni
        if ($request->has('comment') && !empty($request->comment)) {
            $ticket->comments()->create([
                'user_id' => Auth::id(),
                'content' => $request->comment,
                'is_private' => $request->has('is_private') ? true : false,
            ]);
        }
        
        return redirect()->route('admin.tickets.show', $ticket->id)
                        ->with('success', 'Ticket mis à jour avec succès.');
    }

    /**
     * Supprime un ticket.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        
        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket supprimé avec succès.');
    }
    
    /**
     * Ajoute un commentaire à un ticket
     */
    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'is_private' => 'nullable|boolean',
            'is_solution' => 'nullable|boolean',
            'attachment' => 'nullable|file|max:2048',
        ]);
        
        $ticket = Ticket::findOrFail($id);
        
        $comment = new TicketComment();
        $comment->ticket_id = $ticket->id;
        $comment->user_id = Auth::id();
        $comment->content = $validated['content'];
        $comment->is_private = $request->has('is_private');
        $comment->is_solution = $request->has('is_solution');
        
        // Traitement de la pièce jointe
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('ticket_attachments', $filename, 'public');
            $comment->attachment = $filename;
        }
        
        $comment->save();
        
        // Mise à jour du statut du ticket si marqué comme solution
        if ($comment->is_solution && $ticket->status !== 'fermé') {
            $ticket->status = 'résolu';
            $ticket->resolved_at = Carbon::now();
            $ticket->save();
        }
        
        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }
    
    /**
     * Change le statut d'un ticket
     */
    public function changeStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:ouvert,en-cours,résolu,fermé',
        ]);
        
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $ticket->status = $validated['status'];
        
        // Mise à jour des timestamps
        if ($validated['status'] === 'résolu' && !$ticket->resolved_at) {
            $ticket->resolved_at = Carbon::now();
        } elseif ($validated['status'] === 'fermé' && !$ticket->closed_at) {
            $ticket->closed_at = Carbon::now();
        }
        
        $ticket->save();
        
        // Ajouter un commentaire automatique sur le changement de statut
        $statusMap = [
            'ouvert' => 'ouvert',
            'en-cours' => 'en cours',
            'résolu' => 'résolu',
            'fermé' => 'fermé'
        ];
        
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => "Le statut du ticket a été changé de '{$statusMap[$oldStatus]}' à '{$statusMap[$validated['status']]}'.",
            'is_private' => true,
        ]);
        
        return redirect()->back()->with('success', 'Statut du ticket mis à jour.');
    }
    
    /**
     * Assigne un ticket à un utilisateur
     */
    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);
        
        $ticket = Ticket::findOrFail($id);
        $oldAssignee = $ticket->assignedUser ? $ticket->assignedUser->name : 'personne';
        $ticket->assigned_to = $validated['assigned_to'];
        $ticket->save();
        
        $newAssignee = User::find($validated['assigned_to'])->name;
        
        // Ajouter un commentaire automatique
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => "Le ticket a été réassigné de {$oldAssignee} à {$newAssignee}.",
            'is_private' => true,
        ]);
        
        return redirect()->back()->with('success', 'Ticket assigné avec succès.');
    }
}
