@extends('admin.layout')

@section('title', 'Gestion des utilisateurs')

@section('page_title', 'Gestion des utilisateurs')

@section('content_body')
<div class="container-fluid">
    <!-- Messages de notification -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Succès !</h5>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Erreur !</h5>
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>Liste des utilisateurs
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-user-plus mr-1"></i> Nouvel utilisateur
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="btn-group">
                                <a href="{{ request()->url() }}" class="btn {{ !request('role') ? 'btn-primary' : 'btn-default' }}">
                                    <i class="fas fa-users mr-1"></i> Tous
                                    <span class="badge badge-light ml-1">{{ \App\Models\User::count() }}</span>
                                </a>
                                <a href="{{ request()->url() . '?role=admin' }}" class="btn {{ request('role') == 'admin' ? 'btn-primary' : 'btn-default' }}">
                                    <i class="fas fa-user-shield mr-1"></i> Admins
                                    <span class="badge badge-light ml-1">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                                </a>
                                <a href="{{ request()->url() . '?role=staff' }}" class="btn {{ request('role') == 'staff' ? 'btn-primary' : 'btn-default' }}">
                                    <i class="fas fa-user-tie mr-1"></i> Staff
                                    <span class="badge badge-light ml-1">{{ \App\Models\User::where('role', 'staff')->count() }}</span>
                                </a>
                                <a href="{{ request()->url() . '?role=client' }}" class="btn {{ request('role') == 'client' ? 'btn-primary' : 'btn-default' }}">
                                    <i class="fas fa-user mr-1"></i> Clients
                                    <span class="badge badge-light ml-1">{{ \App\Models\User::where('role', 'client')->count() }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ request()->url() }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                    @if(request('role'))
                                        <input type="hidden" name="role" value="{{ request('role') }}">
                                    @endif
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 60px" class="text-center">ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th style="width: 120px" class="text-center">Rôle</th>
                                    <th style="width: 180px" class="text-center">Client associé</th>
                                    <th style="width: 130px" class="text-center">Date d'inscription</th>
                                    <th style="width: 120px" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        <div class="user-block">
                                            <img class="img-circle" src="{{ asset('dist/img/avatar.png') }}" alt="Avatar">
                                            <span class="username">{{ $user->name }}</span>
                                            <span class="description">Dernière connexion: {{ $user->last_login_at ?? 'Jamais' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}" class="text-primary">
                                            <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($user->role == 'admin')
                                            <span class="badge badge-danger"><i class="fas fa-user-shield mr-1"></i>Administrateur</span>
                                        @elseif($user->role == 'staff')
                                            <span class="badge badge-info"><i class="fas fa-user-tie mr-1"></i>Staff</span>
                                        @elseif($user->role == 'client')
                                            <span class="badge badge-success"><i class="fas fa-user mr-1"></i>Client</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->client)
                                            <a href="{{ route('admin.clients.show', $user->client->id) }}" class="btn btn-xs btn-outline-primary">
                                                <i class="fas fa-building mr-1"></i>{{ $user->client->name }}
                                            </a>
                                        @else
                                            @if($user->role === 'client')
                                                <button type="button" 
                                                    class="btn btn-xs btn-outline-success"
                                                    data-toggle="modal" 
                                                    data-target="#assign-client-modal-{{ $user->id }}">
                                                    <i class="fas fa-plus mr-1"></i>Associer
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->id() !== $user->id)
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $user->id }}" data-toggle="tooltip" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun utilisateur trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<!-- Modals de suppression -->
@foreach($users as $user)
    @if(auth()->id() !== $user->id)
    <div class="modal fade" id="delete-modal-{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Supprimer l'utilisateur
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i> Cette action est irréversible.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Modal d'assignation de client -->
    @if($user->role === 'client' && !$user->client_id)
    <div class="modal fade" id="assign-client-modal-{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">
                        <i class="fas fa-building mr-2"></i>Associer à un client
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.users.change-to-client', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Sélectionnez un client à associer à l'utilisateur <strong>{{ $user->name }}</strong>.</p>
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" id="client_id" class="form-control select2" style="width: 100%;" required>
                                <option value="">Sélectionnez un client</option>
                                @foreach(\App\Models\Client::orderBy('name')->get() as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection

@section('custom_js')
<script>
$(function() {
    // Initialisation des tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialisation des selects avancés
    $('.select2').select2({
        theme: 'bootstrap4'
    });
});
</script>
@endsection 