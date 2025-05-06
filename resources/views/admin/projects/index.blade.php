@extends('admin.layout')

@section('title', 'Gestion des projets')

@section('page_title', 'Gestion des projets')

@section('content_body')
    <!-- Messages de notification -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Entête avec bouton d'ajout -->
    <div class="row mb-4">
        <div class="col-md-6">
            <p class="text-muted">Gérez tous vos projets en un seul endroit</p>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-project">
                <i class="fas fa-plus mr-1"></i> Ajouter un projet
            </button>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des projets</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Rechercher">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Progression</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects ?? [] as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->client->name ?? 'N/A' }}</td>
                        <td>{{ number_format($project->price, 2) }} €</td>
                        <td>
                            @if($project->status == 'en-cours')
                                <span class="badge badge-warning">En cours</span>
                            @elseif($project->status == 'terminé')
                                <span class="badge badge-success">Terminé</span>
                            @else
                                <span class="badge badge-secondary">En attente</span>
                            @endif
                        </td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <small>{{ $project->progress }}%</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info view-project-btn" data-project-id="{{ $project->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning edit-project-btn" data-project-id="{{ $project->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-project-btn" data-project-id="{{ $project->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucun projet trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal d'ajout/édition de projet -->
    <div class="modal fade" id="modal-add-project">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un nouveau projet</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="projectForm" action="{{ route('admin.projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="projectMethod" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Titre du projet</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select class="form-control select2" id="client_id" name="client_id">
                                <option value="" selected disabled>Sélectionner un client</option>
                                @foreach ($clients ?? [] as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Montant (€)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="status">Statut</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="en-cours">En cours</option>
                                <option value="terminé">Terminé</option>
                                <option value="en-attente">En attente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal de visualisation du projet -->
    <div class="modal fade" id="viewProjectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Détails du projet</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Titre:</label>
                                <p id="view-title" class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client:</label>
                                <p id="view-client" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Montant:</label>
                                <p id="view-price" class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Statut:</label>
                                <p id="view-status" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Progression:</label>
                                <div class="progress">
                                    <div id="view-progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <span id="view-progress-text" class="mt-1 d-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description:</label>
                                <p id="view-description" class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tâches associées:</label>
                                <ul id="view-tasks" class="form-control-static"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function () {
        // Initialisation de DataTables si nécessaire
        if ($.fn.dataTable) {
            $('.table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
                }
            });
        }
        
        // Initialisation de Select2 si nécessaire
        if ($.fn.select2) {
            $('.select2').select2();
        }
        
        // Affichage du modal d'édition avec les données
        $('.edit-project-btn').on('click', function() {
            const projectId = $(this).data('project-id');
            const modal = $('#modal-add-project');
            
            // Récupération des données du projet via Ajax
            $.ajax({
                url: `/admin/projects/${projectId}/edit`,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        const project = response.project;
                        
                        modal.find('.modal-title').text('Modifier le projet');
                        modal.find('#projectMethod').val('PUT');
                        modal.find('#title').val(project.title);
                        modal.find('#client_id').val(project.client_id).trigger('change');
                        modal.find('#price').val(project.price);
                        modal.find('#status').val(project.status);
                        modal.find('#description').val(project.description);
                        
                        // Mise à jour de l'URL du formulaire pour l'édition
                        modal.find('#projectForm').attr('action', `{{ url('admin/projects') }}/${projectId}`);
                        
                        modal.modal('show');
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Impossible de récupérer les données du projet',
                        icon: 'error'
                    });
                }
            });
        });
        
        // Affichage du modal d'ajout (réinitialiser le formulaire)
        $('[data-target="#modal-add-project"]').on('click', function() {
            const modal = $('#modal-add-project');
            modal.find('.modal-title').text('Ajouter un nouveau projet');
            modal.find('#projectMethod').val('POST');
            modal.find('#projectForm').attr('action', "{{ route('admin.projects.store') }}");
            modal.find('#projectForm')[0].reset();
            if ($.fn.select2) {
                modal.find('#client_id').val('').trigger('change');
            }
        });
        
        // Affichage des détails du projet
        $('.view-project-btn').on('click', function() {
            const projectId = $(this).data('project-id');
            const modal = $('#viewProjectModal');
            
            $.ajax({
                url: `/admin/projects/${projectId}`,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        const project = response.project;
                        
                        modal.find('#view-title').text(project.title);
                        modal.find('#view-client').text(project.client ? project.client.name : 'Non assigné');
                        modal.find('#view-price').text(parseFloat(project.price).toFixed(2) + ' €');
                        
                        // Affichage du statut avec badge
                        let statusText = '';
                        if (project.status === 'en-cours') {
                            statusText = '<span class="badge badge-warning">En cours</span>';
                        } else if (project.status === 'terminé') {
                            statusText = '<span class="badge badge-success">Terminé</span>';
                        } else {
                            statusText = '<span class="badge badge-secondary">En attente</span>';
                        }
                        modal.find('#view-status').html(statusText);
                        
                        // Barre de progression
                        modal.find('#view-progress-bar')
                            .css('width', project.progress + '%')
                            .attr('aria-valuenow', project.progress);
                        modal.find('#view-progress-text').text(project.progress + '%');
                        
                        // Description
                        modal.find('#view-description').text(project.description || 'Aucune description disponible');
                        
                        // Tâches associées
                        const tasksList = modal.find('#view-tasks');
                        tasksList.empty();
                        
                        if (project.tasks && project.tasks.length > 0) {
                            project.tasks.forEach(task => {
                                tasksList.append(`<li>${task.title}</li>`);
                            });
                        } else {
                            tasksList.append('<li>Aucune tâche associée</li>');
                        }
                        
                        modal.modal('show');
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Impossible de récupérer les détails du projet',
                        icon: 'error'
                    });
                }
            });
        });
        
        // Suppression d'un projet
        $('.delete-project-btn').on('click', function() {
            const projectId = $(this).data('project-id');
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est irréversible !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Créer un formulaire temporaire pour la suppression
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/projects/${projectId}`;
                    form.style.display = 'none';
                    
                    // Ajouter le jeton CSRF et la méthode DELETE
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    // Ajouter le formulaire au document et le soumettre
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
        
        // Gestion de la soumission du formulaire de projet (ajout et modification)
        $('#projectForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const method = $('#projectMethod').val();
            const formData = form.serialize();
            
            $.ajax({
                url: url,
                type: method === 'PUT' ? 'POST' : 'POST', // Toujours utiliser POST mais avec _method=PUT
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Succès',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Fermer le modal
                            $('#modal-add-project').modal('hide');
                            // Recharger la page
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur',
                            text: response.message || 'Une erreur est survenue',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur est survenue';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        title: 'Erreur',
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
@stop 