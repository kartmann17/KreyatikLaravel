@extends('admin.layout')

@section('title', 'Gestion des clients')

@section('page_title', 'Gestion des clients')

@section('content_body')
    <!-- Card pour la recherche et l'ajout -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des clients</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Ajouter un client
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.clients.index') }}" method="GET" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Messages de notification -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Succès!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Tableau des clients -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Nom</th>
                                    <th>Entreprise</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Projets</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->company ?? 'N/A' }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone ?? 'N/A' }}</td>
                                        <td>{{ $client->projects->count() ?? 0 }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $client->id }}" data-name="{{ $client->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun client trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($clients) && is_object($clients) && method_exists($clients, 'links'))
                        <div class="mt-4">
                            {{ $clients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer <span id="clientName"></span> ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function() {
        // Initialisation de DataTables si disponible
        if ($.fn.DataTable) {
            $('.table').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
                }
            });
        }
        
        // Configuration du modal de suppression
        $('#deleteModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');
            
            const modal = $(this);
            modal.find('#clientName').text(name);
            modal.find('#deleteForm').attr('action', `/admin/clients/${id}`);
        });
    });
</script>
@stop 