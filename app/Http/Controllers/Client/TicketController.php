<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Affiche la liste des tickets du client.
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';
        
        $query = Ticket::with(['project', 'assignedUser'])
                      ->where('client_id', Auth::user()->client_id ?? 0);
        
        // Filtrer par statut si nécessaire
        if ($status !== 'all') {
            $query->where('status', $status);
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
            'total' => Ticket::where('client_id', Auth::user()->client_id ?? 0)->count(),
            'open' => Ticket::where('client_id', Auth::user()->client_id ?? 0)->open()->count(),
            'in_progress' => Ticket::where('client_id', Auth::user()->client_id ?? 0)->inProgress()->count(),
            'resolved' => Ticket::where('client_id', Auth::user()->client_id ?? 0)->resolved()->count(),
            'closed' => Ticket::where('client_id', Auth::user()->client_id ?? 0)->closed()->count(),
        ];
        
        return view('client.tickets.index', compact('tickets', 'stats', 'status'));
    }

    /**
     * Affiche le formulaire de création d'un ticket.
     */
    public function create()
    {
        // Récupérer uniquement les projets du client
        $projects = Project::where('client_id', Auth::user()->client_id ?? 0)
                         ->orderBy('title')
                         ->get();
        
        return view('client.tickets.create', compact('projects'));
    }

    /**
     * Enregistre un nouveau ticket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'priority' => 'required|in:basse,moyenne,haute,urgente',
            'attachment' => 'nullable|file|max:2048',
        ]);
        
        // Vérifier que le projet appartient bien au client
        if ($request->project_id) {
            $project = Project::find($request->project_id);
            if ($project->client_id != Auth::user()->client_id) {
                return redirect()->back()
                    ->withErrors(['project_id' => 'Ce projet n\'appartient pas à votre compte.'])
                    ->withInput();
            }
        }
        
        $ticket = new Ticket();
        $ticket->title = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->client_id = Auth::user()->client_id;
        $ticket->project_id = $validated['project_id'];
        $ticket->priority = $validated['priority'];
        $ticket->status = 'ouvert';
        $ticket->created_by = Auth::id();
        $ticket->browser = $request->header('User-Agent');
        $ticket->ip_address = $request->ip();
        $ticket->save();
        
        // Création du premier commentaire avec la description
        $comment = new TicketComment();
        $comment->ticket_id = $ticket->id;
        $comment->user_id = Auth::id();
        $comment->content = $validated['description'];
        
        // Traitement de la pièce jointe
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('ticket_attachments', $filename, 'public');
            $comment->attachment = $filename;
        }
        
        $comment->save();
        
        return redirect()->route('client.tickets.show', $ticket->id)
                        ->with('success', 'Ticket créé avec succès. Notre équipe a été notifiée et reviendra vers vous dès que possible.');
    }

    /**
     * Affiche les détails d'un ticket.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['project', 'assignedUser', 'comments' => function($query) {
                            $query->with('user')->where('is_private', false)->orderBy('created_at');
                        }])
                      ->where('client_id', Auth::user()->client_id ?? 0)
                      ->findOrFail($id);
        
        return view('client.tickets.show', compact('ticket'));
    }

    /**
     * Ajoute un commentaire à un ticket.
     */
    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);
        
        $ticket = Ticket::where('client_id', Auth::user()->client_id ?? 0)
                      ->findOrFail($id);
        
        $comment = new TicketComment();
        $comment->ticket_id = $ticket->id;
        $comment->user_id = Auth::id();
        $comment->content = $validated['content'];
        $comment->is_private = false;
        
        // Traitement de la pièce jointe
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('ticket_attachments', $filename, 'public');
            $comment->attachment = $filename;
        }
        
        $comment->save();
        
        // Si le ticket était résolu ou fermé, le réouvrir
        if (in_array($ticket->status, ['résolu', 'fermé'])) {
            $ticket->status = 'ouvert';
            $ticket->save();
            
            // Ajouter un commentaire automatique
            $ticket->comments()->create([
                'user_id' => Auth::id(),
                'content' => "Le ticket a été réouvert suite à un nouveau commentaire du client.",
                'is_private' => true,
            ]);
        }
        
        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }
    
    /**
     * Permet au client de fermer un ticket
     */
    public function close($id)
    {
        $ticket = Ticket::where('client_id', Auth::user()->client_id ?? 0)
                      ->findOrFail($id);
        
        $ticket->status = 'fermé';
        $ticket->closed_at = now();
        $ticket->save();
        
        // Ajouter un commentaire automatique
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => "Le ticket a été fermé par le client.",
            'is_private' => false,
        ]);
        
        return redirect()->back()->with('success', 'Ticket fermé avec succès.');
    }
    
    /**
     * Permet au client de télécharger une pièce jointe
     */
    public function downloadAttachment($commentId)
    {
        $comment = TicketComment::findOrFail($commentId);
        $ticket = Ticket::findOrFail($comment->ticket_id);
        
        // Vérifier que le ticket appartient bien au client
        if ($ticket->client_id != Auth::user()->client_id) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette pièce jointe.');
        }
        
        if (empty($comment->attachment)) {
            abort(404, 'Aucune pièce jointe trouvée.');
        }
        
        $path = storage_path('app/public/ticket_attachments/' . $comment->attachment);
        
        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé.');
        }
        
        return response()->download($path);
    }
}
