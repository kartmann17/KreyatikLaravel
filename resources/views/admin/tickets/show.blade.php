@extends('admin.layout')

@section('title', 'Détail du ticket #' . $ticket->ticket_number)

@section('page_title', 'Détail du ticket #' . $ticket->ticket_number)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Ticket #{{ $ticket->ticket_number }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content_body')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <!-- Ticket Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $ticket->title }}</h3>
                <span class="float-right">
                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user mr-1"></i> Client:</strong> {{ $ticket->client->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-project-diagram mr-1"></i> Projet:</strong> {{ $ticket->project->title ?? 'N/A' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-alt mr-1"></i> Créé le:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-user-circle mr-1"></i> Créé par:</strong> {{ $ticket->creator->name ?? 'N/A' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-info-circle mr-1"></i> Statut:</strong>
                        @if ($ticket->status === 'ouvert')
                            <span class="badge badge-info">Ouvert</span>
                        @elseif ($ticket->status === 'en-cours')
                            <span class="badge badge-warning">En cours</span>
                        @elseif ($ticket->status === 'résolu')
                            <span class="badge badge-success">Résolu</span>
                            <small class="text-muted ml-1">({{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i') : 'N/A' }})</small>
                        @elseif ($ticket->status === 'fermé')
                            <span class="badge badge-secondary">Fermé</span>
                            <small class="text-muted ml-1">({{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : 'N/A' }})</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-exclamation-circle mr-1"></i> Priorité:</strong>
                        @if ($ticket->priority === 'basse')
                            <span class="badge badge-info">Basse</span>
                        @elseif ($ticket->priority === 'moyenne')
                            <span class="badge badge-primary">Moyenne</span>
                        @elseif ($ticket->priority === 'haute')
                            <span class="badge badge-warning">Haute</span>
                        @elseif ($ticket->priority === 'urgente')
                            <span class="badge badge-danger">Urgente</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user-tag mr-1"></i> Assigné à:</strong> {{ $ticket->assignedUser->name ?? 'Non assigné' }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-browser mr-1"></i> Navigateur:</strong> <span class="text-muted">{{ $ticket->browser }}</span>
                    </div>
                </div>
                
                <hr>
                
                <h5>Description</h5>
                <div class="callout callout-info">
                    {!! nl2br(e($ticket->description)) !!}
                </div>
            </div>
        </div>
        
        <!-- Comments -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Commentaires et activité</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($ticket->comments->sortByDesc('created_at') as $comment)
                        <div class="time-label">
                            <span class="bg-primary">{{ $comment->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <i class="fas {{ $comment->is_private ? 'fa-eye-slash bg-secondary' : 'fa-comment bg-blue' }}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->format('H:i') }}</span>
                                <h3 class="timeline-header">
                                    <strong>{{ $comment->user->name }}</strong>
                                    @if($comment->is_private)
                                        <span class="badge badge-secondary ml-2">Privé</span>
                                    @endif
                                    @if($comment->is_solution)
                                        <span class="badge badge-success ml-2">Solution</span>
                                    @endif
                                </h3>
                                <div class="timeline-body">
                                    {!! nl2br(e($comment->content)) !!}
                                    
                                    @if($comment->hasAttachment())
                                        <div class="mt-2">
                                            <a href="{{ url('storage/ticket_attachments/' . $comment->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-paperclip"></i> Pièce jointe
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <em>Aucun commentaire pour le moment.</em>
                        </div>
                    @endforelse
                    
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
                
                <!-- Comment Form -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Ajouter un commentaire</h3>
                    </div>
                    <form action="{{ route('admin.tickets.comment.add', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <textarea name="content" class="form-control" rows="3" placeholder="Votre commentaire..."></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                    <label class="custom-file-label" for="attachment">Pièce jointe (optionnel)</label>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_private" name="is_private">
                                <label class="form-check-label" for="is_private">Commentaire privé (visible uniquement par l'équipe)</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_solution" name="is_solution">
                                <label class="form-check-label" for="is_solution">Marquer comme solution</label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Status Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Actions</h3>
            </div>
            <div class="card-body">
                <h5>Changer le statut</h5>
                <div class="btn-group d-flex mb-3">
                    <form action="{{ route('admin.tickets.status.change', $ticket->id) }}" method="POST" class="mr-1">
                        @csrf
                        <input type="hidden" name="status" value="ouvert">
                        <button type="submit" class="btn btn-info {{ $ticket->status === 'ouvert' ? 'disabled' : '' }}">
                            Ouvert
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.tickets.status.change', $ticket->id) }}" method="POST" class="mr-1">
                        @csrf
                        <input type="hidden" name="status" value="en-cours">
                        <button type="submit" class="btn btn-warning {{ $ticket->status === 'en-cours' ? 'disabled' : '' }}">
                            En cours
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.tickets.status.change', $ticket->id) }}" method="POST" class="mr-1">
                        @csrf
                        <input type="hidden" name="status" value="résolu">
                        <button type="submit" class="btn btn-success {{ $ticket->status === 'résolu' ? 'disabled' : '' }}">
                            Résolu
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.tickets.status.change', $ticket->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="fermé">
                        <button type="submit" class="btn btn-secondary {{ $ticket->status === 'fermé' ? 'disabled' : '' }}">
                            Fermé
                        </button>
                    </form>
                </div>
                
                <h5>Assignation</h5>
                <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <select name="assigned_to" class="form-control">
                            <option value="">-- Non assigné --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Assigner</button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal">
                        <i class="fas fa-trash"></i> Supprimer le ticket
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Related Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informations complémentaires</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-unbordered">
                    @if($ticket->client)
                        <li class="list-group-item">
                            <strong>Client:</strong> <a href="{{ route('admin.clients.show', $ticket->client_id) }}">{{ $ticket->client->name }}</a>
                        </li>
                    @endif
                    
                    @if($ticket->project)
                        <li class="list-group-item">
                            <strong>Projet:</strong> <a href="{{ route('admin.projects.show', $ticket->project_id) }}">{{ $ticket->project->title }}</a>
                        </li>
                    @endif
                    
                    <li class="list-group-item">
                        <strong>IP:</strong> {{ $ticket->ip_address }}
                    </li>
                    
                    <li class="list-group-item">
                        <strong>Navigateur:</strong> <small>{{ $ticket->browser }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-modal-label">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer le ticket <strong>{{ $ticket->ticket_number }}</strong> ?
                <br>Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        // Initialize file input
        bsCustomFileInput.init();
    });
</script>
@endsection 