@extends('admin.layout')

@section('title', 'Gestion des tickets')

@section('page_title', 'Gestion des tickets')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Gestion des tickets</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tickets</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content_body')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['open'] }}</h3>
                <p>Tickets ouverts</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <a href="{{ route('admin.tickets.index', ['status' => 'ouvert']) }}" class="small-box-footer">
                Voir tous <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['in_progress'] }}</h3>
                <p>Tickets en cours</p>
            </div>
            <div class="icon">
                <i class="fas fa-cog"></i>
            </div>
            <a href="{{ route('admin.tickets.index', ['status' => 'en-cours']) }}" class="small-box-footer">
                Voir tous <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['resolved'] }}</h3>
                <p>Tickets résolus</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.tickets.index', ['status' => 'résolu']) }}" class="small-box-footer">
                Voir tous <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['high_priority'] }}</h3>
                <p>Priorité haute/urgente</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('admin.tickets.index', ['priority' => 'haute']) }}" class="small-box-footer">
                Voir tous <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des tickets</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.tickets.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Rechercher..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau ticket
                    </a>
                    
                    <div class="float-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Statut: {{ $status === 'all' ? 'Tous' : ucfirst($status) }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item {{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}">Tous</a>
                                <a class="dropdown-item {{ $status === 'ouvert' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'ouvert'])) }}">Ouvert</a>
                                <a class="dropdown-item {{ $status === 'en-cours' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'en-cours'])) }}">En cours</a>
                                <a class="dropdown-item {{ $status === 'résolu' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'résolu'])) }}">Résolu</a>
                                <a class="dropdown-item {{ $status === 'fermé' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'fermé'])) }}">Fermé</a>
                            </div>
                        </div>
                        
                        <div class="btn-group ml-2">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Priorité: {{ $priority === 'all' ? 'Toutes' : ucfirst($priority) }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item {{ $priority === 'all' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('priority', 'page'), ['priority' => 'all'])) }}">Toutes</a>
                                <a class="dropdown-item {{ $priority === 'basse' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('priority', 'page'), ['priority' => 'basse'])) }}">Basse</a>
                                <a class="dropdown-item {{ $priority === 'moyenne' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('priority', 'page'), ['priority' => 'moyenne'])) }}">Moyenne</a>
                                <a class="dropdown-item {{ $priority === 'haute' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('priority', 'page'), ['priority' => 'haute'])) }}">Haute</a>
                                <a class="dropdown-item {{ $priority === 'urgente' ? 'active' : '' }}" href="{{ route('admin.tickets.index', array_merge(request()->except('priority', 'page'), ['priority' => 'urgente'])) }}">Urgente</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Client</th>
                                <th>Projet</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Assigné à</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_number }}</td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}">
                                            {{ $ticket->title }}
                                        </a>
                                    </td>
                                    <td>{{ $ticket->client->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->project->title ?? 'N/A' }}</td>
                                    <td>
                                        @if ($ticket->status === 'ouvert')
                                            <span class="badge badge-info">Ouvert</span>
                                        @elseif ($ticket->status === 'en-cours')
                                            <span class="badge badge-warning">En cours</span>
                                        @elseif ($ticket->status === 'résolu')
                                            <span class="badge badge-success">Résolu</span>
                                        @elseif ($ticket->status === 'fermé')
                                            <span class="badge badge-secondary">Fermé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ticket->priority === 'basse')
                                            <span class="badge badge-info">Basse</span>
                                        @elseif ($ticket->priority === 'moyenne')
                                            <span class="badge badge-primary">Moyenne</span>
                                        @elseif ($ticket->priority === 'haute')
                                            <span class="badge badge-warning">Haute</span>
                                        @elseif ($ticket->priority === 'urgente')
                                            <span class="badge badge-danger">Urgente</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->assignedUser->name ?? 'Non assigné' }}</td>
                                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $ticket->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="delete-modal-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Aucun ticket trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $tickets->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 