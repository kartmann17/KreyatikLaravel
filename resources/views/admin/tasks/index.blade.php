@extends('admin.layout')

@section('title', 'Gestion des tâches')

@section('page_title', 'Gestion des tâches')

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

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des tâches</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#taskModal">
                            <i class="fas fa-plus mr-1"></i> Nouvelle tâche
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2" id="projectFilter">
                                    <option value="">Tous les projets</option>
                                    @foreach ($projects ?? [] as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2" id="statusFilter">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending">En attente</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="completed">Terminé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2" id="priorityFilter">
                                    <option value="">Toutes les priorités</option>
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
                                <div class="input-group-append">
                                    <button class="btn btn-default" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tasksTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">Titre</th>
                                    <th style="width: 15%">Projet</th>
                                    <th style="width: 10%">Priorité</th>
                                    <th style="width: 10%">Statut</th>
                                    <th style="width: 10%">Échéance</th>
                                    <th style="width: 10%">Progression</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks ?? [] as $task)
                                <tr>
                                    <td>{{ $task->id }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->project->title ?? 'N/A' }}</td>
                                    <td>
                                        @if($task->priority == 'low')
                                            <span class="badge badge-info">Basse</span>
                                        @elseif($task->priority == 'medium')
                                            <span class="badge badge-success">Moyenne</span>
                                        @elseif($task->priority == 'high')
                                            <span class="badge badge-warning">Haute</span>
                                        @elseif($task->priority == 'urgent')
                                            <span class="badge badge-danger">Urgente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->status == 'a-faire')
                                            <span class="badge badge-secondary">En attente</span>
                                        @elseif($task->status == 'en-cours')
                                            <span class="badge badge-primary">En cours</span>
                                        @elseif($task->status == 'a-tester')
                                            <span class="badge badge-info">À tester</span>
                                        @elseif($task->status == 'termine')
                                            <span class="badge badge-success">Terminé</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->due_date ? date('d/m/Y', strtotime($task->due_date)) : 'N/A' }}</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-gradient-success" role="progressbar" 
                                                style="width: {{ $task->progress }}%" 
                                                aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small>{{ $task->progress }}%</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                                data-target="#viewTaskModal" data-task-id="{{ $task->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" 
                                                data-target="#taskModal" data-task-id="{{ $task->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success task-progress-btn" 
                                                data-task-id="{{ $task->id }}" data-progress="{{ $task->progress }}">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-task-btn" 
                                                data-task-id="{{ $task->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucune tâche trouvée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Ajouter une tâche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="taskForm" action="{{ route('admin.tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="taskMethod" value="POST">
                    <input type="hidden" name="task_id" id="taskId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id">Projet <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="project_id" name="project_id" required>
                                        <option value="" selected disabled>Sélectionner un projet</option>
                                        @foreach ($projects ?? [] as $project)
                                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="priority">Priorité</label>
                                    <select class="form-control" id="priority" name="priority">
                                        <option value="low">Basse</option>
                                        <option value="medium" selected>Moyenne</option>
                                        <option value="high">Haute</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="a-faire" selected>En attente</option>
                                        <option value="en-cours">En cours</option>
                                        <option value="a-tester">À tester</option>
                                        <option value="termine">Terminé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="due_date">Date d'échéance</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="progress">Progression</label>
                                    <input type="range" class="form-control-range" id="progress" name="progress" min="0" max="100" value="0">
                                    <div class="text-center mt-1" id="progressValue">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Task Modal -->
    <div class="modal fade" id="viewTaskModal" tabindex="-1" role="dialog" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTaskModalLabel">Détails de la tâche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Titre:</label>
                                <p id="view-title" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Projet:</label>
                                <p id="view-project" class="form-control-static"></p>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Priorité:</label>
                                <p id="view-priority" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Statut:</label>
                                <p id="view-status" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date d'échéance:</label>
                                <p id="view-due-date" class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Progression:</label>
                                <div class="progress">
                                    <div id="view-progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <span id="view-progress-text" class="mt-1 d-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Update Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Mettre à jour la progression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="progressForm" action="{{ route('admin.tasks.update.progress') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" id="progressTaskId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="taskProgress">Progression</label>
                            <input type="range" class="form-control-range" id="taskProgress" name="progress" min="0" max="100" value="0">
                            <div class="text-center mt-1" id="taskProgressValue">0%</div>
                        </div>
                        <div class="form-group">
                            <label for="progressNote">Note (optionnel)</label>
                            <textarea class="form-control" id="progressNote" name="note" rows="3" placeholder="Ajouter une note sur l'avancement..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function() {
        // Initialisation de DataTables
        if ($.fn.DataTable) {
            $('#tasksTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
                }
            });
        }
        
        // Initialisation de Select2
        if ($.fn.select2) {
            $('.select2').select2();
        }
        
        // Mise à jour de la valeur de progression
        $('#progress').on('input', function() {
            $('#progressValue').text($(this).val() + '%');
        });
        
        $('#taskProgress').on('input', function() {
            $('#taskProgressValue').text($(this).val() + '%');
        });
        
        // Gestion de la soumission du formulaire de tâche (ajout et modification)
        $('#taskForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const method = $('#taskMethod').val();
            const formData = form.serialize();
            
            $.ajax({
                url: url,
                type: method === 'PUT' ? 'POST' : 'POST', // Toujours utiliser POST mais avec _method=PUT
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Succès',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirection vers la page des tâches
                            window.location.href = "{{ route('admin.tasks.index') }}";
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
        
        // Affichage du modal d'édition avec les données
        $('#taskModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const taskId = button.data('task-id');
            const modal = $(this);
            
            if (taskId) {
                modal.find('.modal-title').text('Modifier la tâche');
                modal.find('#taskMethod').val('PUT');
                modal.find('#taskId').val(taskId);
                
                // Récupération des données de la tâche via Ajax
                $.ajax({
                    url: `/admin/tasks/${taskId}/edit`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const task = response.task;
                            modal.find('#title').val(task.title);
                            modal.find('#description').val(task.description);
                            modal.find('#project_id').val(task.project_id).trigger('change');
                            modal.find('#priority').val(task.priority);
                            modal.find('#status').val(task.status);
                            modal.find('#due_date').val(task.due_date);
                            modal.find('#progress').val(task.progress);
                            modal.find('#progressValue').text(task.progress + '%');
                            
                            // Mise à jour de l'URL du formulaire pour l'édition
                            modal.find('#taskForm').attr('action', `{{ url('admin/tasks') }}/${taskId}`);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Impossible de récupérer les données de la tâche',
                            icon: 'error'
                        });
                    }
                });
            } else {
                modal.find('.modal-title').text('Ajouter une tâche');
                modal.find('#taskMethod').val('POST');
                modal.find('#taskId').val('');
                modal.find('#taskForm').trigger('reset');
                modal.find('#progressValue').text('0%');
            }
        });
        
        // Affichage du modal de détails de la tâche
        $('#viewTaskModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const taskId = button.data('task-id');
            const modal = $(this);
            
            $.ajax({
                url: `/admin/tasks/${taskId}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const task = response.task;
                        modal.find('#view-title').text(task.title);
                        modal.find('#view-project').text(task.project ? task.project.title : 'N/A');
                        modal.find('#view-description').text(task.description || 'Aucune description');
                        
                        // Affichage de la priorité avec badge
                        let priorityText = '';
                        if (task.priority === 'low') {
                            priorityText = '<span class="badge badge-info">Basse</span>';
                        } else if (task.priority === 'medium') {
                            priorityText = '<span class="badge badge-success">Moyenne</span>';
                        } else if (task.priority === 'high') {
                            priorityText = '<span class="badge badge-warning">Haute</span>';
                        } else if (task.priority === 'urgent') {
                            priorityText = '<span class="badge badge-danger">Urgente</span>';
                        }
                        modal.find('#view-priority').html(priorityText);
                        
                        // Affichage du statut avec badge
                        let statusText = '';
                        if (task.status === 'a-faire') {
                            statusText = '<span class="badge badge-secondary">En attente</span>';
                        } else if (task.status === 'en-cours') {
                            statusText = '<span class="badge badge-primary">En cours</span>';
                        } else if (task.status === 'a-tester') {
                            statusText = '<span class="badge badge-info">À tester</span>';
                        } else if (task.status === 'termine') {
                            statusText = '<span class="badge badge-success">Terminé</span>';
                        }
                        modal.find('#view-status').html(statusText);
                        
                        // Date d'échéance
                        modal.find('#view-due-date').text(task.due_date || 'Non définie');
                        
                        // Barre de progression
                        modal.find('#view-progress-bar')
                            .css('width', task.progress + '%')
                            .attr('aria-valuenow', task.progress);
                        modal.find('#view-progress-text').text(task.progress + '%');
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Impossible de récupérer les détails de la tâche',
                        icon: 'error'
                    });
                }
            });
        });
        
        // Affichage du modal de mise à jour de la progression
        $('.task-progress-btn').on('click', function(e) {
            e.preventDefault();
            const taskId = $(this).data('task-id');
            const progress = $(this).data('progress');
            
            $('#progressTaskId').val(taskId);
            $('#taskProgress').val(progress);
            $('#taskProgressValue').text(progress + '%');
            $('#progressModal').modal('show');
        });
        
        // Suppression d'une tâche
        $('.delete-task-btn').on('click', function(e) {
            e.preventDefault();
            const taskId = $(this).data('task-id');
            
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
                    $.ajax({
                        url: `/admin/tasks/${taskId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Supprimé !',
                                    'La tâche a été supprimée avec succès.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Erreur !',
                                    response.message || 'Une erreur est survenue.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Erreur !',
                                'Une erreur est survenue lors de la suppression.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        
        // Filtres
        $('#projectFilter, #statusFilter, #priorityFilter').on('change', function() {
            // Logique de filtrage du tableau
            const projectId = $('#projectFilter').val();
            const status = $('#statusFilter').val();
            const priority = $('#priorityFilter').val();
            
            // Réinitialiser le tableau DataTable avec les filtres appliqués
            // Note: L'implémentation exacte dépendra de votre setup DataTables
            $('#tasksTable').DataTable().draw();
        });
        
        // Recherche en temps réel
        $('#searchInput').on('keyup', function() {
            $('#tasksTable').DataTable().search($(this).val()).draw();
        });
    });
</script>
@stop 