@extends('admin.layout')

@section('title', 'Timer & Suivi de temps')

@section('page_title', 'Timer & Suivi de temps')

@section('content_body')
    <!-- Timer Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Chronométrer votre temps</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="project">Projet</label>
                                <select class="form-control select2" id="project" name="project_id">
                                    <option value="" selected disabled>Sélectionner un projet</option>
                                    @foreach ($projects ?? [] as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="task">Tâche</label>
                                <select class="form-control select2" id="task" name="task_id">
                                    <option value="" selected disabled>Sélectionner une tâche</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Description de l'activité...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <div class="timer-display mb-3">00:00:00</div>
                            <div class="btn-group">
                                <button id="startTimer" class="btn btn-success"><i class="fas fa-play mr-1"></i> Démarrer</button>
                                <button id="pauseTimer" class="btn btn-warning" disabled><i class="fas fa-pause mr-1"></i> Pause</button>
                                <button id="stopTimer" class="btn btn-danger" disabled><i class="fas fa-stop mr-1"></i> Arrêter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Logs Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historique de temps</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a href="#" class="dropdown-item">Aujourd'hui</a>
                                <a href="#" class="dropdown-item">Cette semaine</a>
                                <a href="#" class="dropdown-item">Ce mois</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">Tous</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Projet</th>
                                <th>Tâche</th>
                                <th>Description</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentLogs ?? [] as $log)
                            <tr>
                                <td>{{ $log->formatted_started_at }}</td>
                                <td>{{ $log->project->title ?? 'N/A' }}</td>
                                <td>{{ $log->task->title ?? 'N/A' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->formatted_duration }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning edit-log" data-id="{{ $log->id }}" data-project="{{ $log->project ? $log->project->id : '' }}" data-task="{{ $log->task ? $log->task->id : '' }}" data-description="{{ $log->description || '' }}" data-duration="{{ $log->duration }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-log" data-id="{{ $log->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun enregistrement de temps trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Statistics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Temps par projet</h3>
                </div>
                <div class="card-body">
                    <canvas id="projectTimeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Temps par jour</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyTimeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function() {
        // Initialiser Select2
        if ($.fn.select2) {
            $('.select2').select2();
        }
        
        // Variables pour le timer
        let timer;
        let isRunning = false;
        let isPaused = false;
        let startTime;
        let pausedTime = 0;
        let seconds = 0;
        let currentProjectId = null;
        
        // Éléments du DOM
        const timerDisplay = document.querySelector('.timer-display');
        const projectSelect = document.getElementById('project');
        const taskSelect = document.getElementById('task');
        const descriptionInput = document.getElementById('description');
        const startTimerBtn = document.getElementById('startTimer');
        const pauseTimerBtn = document.getElementById('pauseTimer');
        const stopTimerBtn = document.getElementById('stopTimer');
        
        // Charger les tâches quand un projet est sélectionné
        projectSelect.addEventListener('change', function() {
            const projectId = this.value;
            if (projectId) {
                loadTasksByProject(projectId);
            } else {
                // Réinitialiser la liste des tâches
                taskSelect.innerHTML = '<option value="" selected disabled>Sélectionner une tâche</option>';
                taskSelect.disabled = true;
            }
        });
        
        // Fonction pour charger les tâches d'un projet
        function loadTasksByProject(projectId) {
            // Réinitialiser la liste des tâches
            taskSelect.innerHTML = '<option value="" selected disabled>Chargement...</option>';
            taskSelect.disabled = true;
            
            // Requête AJAX pour récupérer les tâches du projet
            axios.get(`/admin/tasks/project/${projectId}`)
                .then(function(response) {
                    if (response.data.success) {
                        const tasks = response.data.tasks;
                        
                        // Réinitialiser le select
                        taskSelect.innerHTML = '<option value="" selected disabled>Sélectionner une tâche</option>';
                        
                        // Ajouter les options de tâches
                        tasks.forEach(function(task) {
                            const option = document.createElement('option');
                            option.value = task.id;
                            option.textContent = task.title;
                            taskSelect.appendChild(option);
                        });
                        
                        // Activer le select
                        taskSelect.disabled = false;
                    }
                })
                .catch(function(error) {
                    console.error('Erreur lors du chargement des tâches:', error);
                    taskSelect.innerHTML = '<option value="" selected disabled>Erreur de chargement</option>';
                });
        }
        
        // Formatage du temps (HH:MM:SS)
        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
        
        // Mise à jour du timer
        function updateTimer() {
            const currentTime = Date.now();
            const elapsedTime = Math.floor((currentTime - startTime) / 1000) + pausedTime;
            seconds = elapsedTime;
            timerDisplay.textContent = formatTime(seconds);
        }
        
        // Démarrer le timer
        startTimerBtn.addEventListener('click', function() {
            if (!projectSelect.value) {
                Swal.fire({
                    title: 'Attention',
                    text: 'Veuillez sélectionner un projet avant de démarrer le chronomètre.',
                    icon: 'warning'
                });
                return;
            }
            
            if (!isRunning) {
                startTime = Date.now();
                timer = setInterval(updateTimer, 1000);
                isRunning = true;
                isPaused = false;
                currentProjectId = projectSelect.value;
                
                // Mise à jour des boutons
                startTimerBtn.disabled = true;
                pauseTimerBtn.disabled = false;
                stopTimerBtn.disabled = false;
                projectSelect.disabled = true;
            } else if (isPaused) {
                startTime = Date.now();
                timer = setInterval(updateTimer, 1000);
                isPaused = false;
                
                // Mise à jour des boutons
                startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Démarrer';
                pauseTimerBtn.disabled = false;
            }
        });
        
        // Mettre en pause le timer
        pauseTimerBtn.addEventListener('click', function() {
            if (isRunning && !isPaused) {
                clearInterval(timer);
                pausedTime = seconds;
                isPaused = true;
                
                // Mise à jour des boutons
                startTimerBtn.disabled = false;
                startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Reprendre';
                pauseTimerBtn.disabled = true;
            }
        });
        
        // Arrêter le timer et enregistrer
        stopTimerBtn.addEventListener('click', function() {
            if (isRunning) {
                clearInterval(timer);
                
                // Enregistrement du temps
                if (seconds > 0) {
                    saveTimeLog();
                }
                
                // Réinitialisation du timer
                isRunning = false;
                isPaused = false;
                pausedTime = 0;
                seconds = 0;
                timerDisplay.textContent = '00:00:00';
                
                // Mise à jour des boutons
                startTimerBtn.disabled = false;
                startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Démarrer';
                pauseTimerBtn.disabled = true;
                stopTimerBtn.disabled = true;
                projectSelect.disabled = false;
            }
        });
        
        // Fonction pour sauvegarder le temps écoulé
        function saveTimeLog() {
            // Si le timer est en cours, le mettre en pause
            if (isRunning && !isPaused) {
                clearInterval(timer);
                isRunning = false;
                isPaused = true;
            }

            // Vérifier si du temps a été enregistré
            if (seconds <= 0) {
                Swal.fire({
                    title: 'Attention',
                    text: 'Aucun temps enregistré',
                    icon: 'warning'
                });
                return;
            }

            // Vérifier si un projet est sélectionné
            const projectId = projectSelect.value;
            if (!projectId) {
                Swal.fire({
                    title: 'Attention',
                    text: 'Veuillez sélectionner un projet',
                    icon: 'warning'
                });
                return;
            }

            const taskId = taskSelect.value;
            const description = descriptionInput.value || 'Temps enregistré via chronomètre';

            // Afficher un indicateur de chargement
            Swal.fire({
                title: 'Enregistrement...',
                text: 'Veuillez patienter',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Envoyer la demande à l'API
            axios.post('{{ route("timer.logTime") }}', {
                project_id: projectId,
                task_id: taskId || null,
                description: description,
                duration: seconds
            })
            .then(response => {
                if (response.data.success) {
                    // Réinitialisation du timer
                    seconds = 0;
                    pausedTime = 0;
                    timerDisplay.textContent = '00:00:00';
                    
                    // Mise à jour de l'interface
                    startTimerBtn.disabled = false;
                    startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Démarrer';
                    pauseTimerBtn.disabled = true;
                    stopTimerBtn.disabled = true;
                    projectSelect.disabled = false;
                    
                    // Notification de succès
                    Swal.fire({
                        title: 'Succès',
                        text: 'Temps enregistré avec succès !',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Recharger les logs récents
                    loadRecentLogs();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                let errorMessage = 'Une erreur est survenue lors de l\'enregistrement.';
                
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }
                
                Swal.fire({
                    title: 'Erreur',
                    text: errorMessage,
                    icon: 'error'
                });
            });
        }
        
        // Fonction pour recharger les logs récents
        function loadRecentLogs() {
            axios.get('{{ route("timer.logs", ["period" => "today"]) }}')
                .then(response => {
                    if (response.data.success) {
                        // Rafraîchir la liste des logs récents
                        const logsTable = document.querySelector('.table tbody');
                        if (logsTable && response.data.logs.length > 0) {
                            logsTable.innerHTML = '';
                            
                            response.data.logs.forEach(log => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${new Date(log.created_at).toLocaleString()}</td>
                                    <td>${log.project ? log.project.title : 'N/A'}</td>
                                    <td>${log.task ? log.task.title : 'N/A'}</td>
                                    <td>${log.description || 'Aucune description'}</td>
                                    <td>${formatTime(log.duration)}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-log" data-id="${log.id}" data-project="${log.project ? log.project.id : ''}" data-task="${log.task ? log.task.id : ''}" data-description="${log.description || ''}" data-duration="${log.duration}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-log" data-id="${log.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                `;
                                logsTable.appendChild(row);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des logs récents:', error);
                });
        }
        
        // Fonction pour supprimer un enregistrement
        function deleteTimeLog(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) {
                axios.delete(`{{ route('timer.destroy', ['id' => '__ID__']) }}`.replace('__ID__', id))
                    .then(response => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                            loadRecentLogs();
                        } else {
                            toastr.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la suppression:', error);
                        toastr.error('Une erreur est survenue lors de la suppression');
                    });
            }
        }

        // Gestionnaire d'événements pour les boutons de suppression
        $(document).on('click', '.delete-log', function() {
            const id = $(this).data('id');
            deleteTimeLog(id);
        });
        
        // Gestion de la modification des logs
        $(document).on('click', '.edit-log', function() {
            const logId = $(this).data('id');
            const projectId = $(this).data('project');
            const taskId = $(this).data('task');
            const description = $(this).data('description');
            const duration = $(this).data('duration');
            
            // Afficher une modale d'édition
            Swal.fire({
                title: 'Modifier l\'enregistrement de temps',
                html: `
                    <div class="form-group">
                        <label for="edit-duration">Durée (secondes)</label>
                        <input type="number" id="edit-duration" class="form-control" value="${duration}">
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea id="edit-description" class="form-control">${description}</textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Enregistrer',
                cancelButtonText: 'Annuler',
                preConfirm: () => {
                    const newDuration = document.getElementById('edit-duration').value;
                    const newDescription = document.getElementById('edit-description').value;
                    
                    if (!newDuration || newDuration <= 0) {
                        Swal.showValidationMessage('La durée doit être supérieure à 0');
                        return false;
                    }
                    
                    return {
                        duration: newDuration,
                        description: newDescription
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoyer la requête de mise à jour
                    axios.put(`{{ route('timer.update', ['id' => '__ID__']) }}`.replace('__ID__', logId), {
                        duration: result.value.duration,
                        description: result.value.description
                    })
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire(
                                'Mis à jour !',
                                'L\'enregistrement de temps a été modifié.',
                                'success'
                            );
                            // Recharger les logs
                            loadRecentLogs();
                        } else {
                            throw new Error(response.data.message || 'Erreur lors de la mise à jour');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        let errorMessage = 'Une erreur est survenue lors de la mise à jour.';
                        
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        
                        Swal.fire(
                            'Erreur!',
                            errorMessage,
                            'error'
                        );
                    });
                }
            });
        });
        
        // Graphiques
        if (window.Chart) {
            // Charger les données pour les graphiques
            axios.get('{{ route("timer.logs", ["period" => "month"]) }}')
                .then(response => {
                    if (response.data.success) {
                        // Données pour le graphique de temps par projet
                        const projectStats = response.data.stats.projectStats || [];
                        const projectLabels = projectStats.map(project => project.name);
                        const projectDurations = projectStats.map(project => project.duration / 3600); // Convertir en heures
                        const projectColors = [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(201, 203, 207, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(255, 205, 86, 0.6)'
                        ];

                        // Graphique temps par projet
                        new Chart(document.getElementById('projectTimeChart'), {
                            type: 'pie',
                            data: {
                                labels: projectLabels.length ? projectLabels : ['Aucune donnée'],
                                datasets: [{
                                    data: projectDurations.length ? projectDurations : [1],
                                    backgroundColor: projectLabels.length ? 
                                        projectLabels.map((_, i) => projectColors[i % projectColors.length]) : 
                                        ['rgba(201, 203, 207, a.6)'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const value = context.raw || 0;
                                                return `${context.label}: ${value.toFixed(2)} heures`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        
                        // Données pour le graphique de temps par jour
                        const dailyStats = response.data.stats.dailyStats || [];
                        // Créer un tableau pour les 7 derniers jours
                        const dayLabels = [];
                        const dayDurations = [];
                        
                        // Obtenir les 7 derniers jours (en utilisant JavaScript natif)
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date();
                            date.setDate(date.getDate() - i);
                            const dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
                            const dayName = dayNames[date.getDay()];
                            
                            dayLabels.push(dayName);
                            
                            // Formater la date pour la comparaison (YYYY-MM-DD)
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');
                            const dateStr = `${year}-${month}-${day}`;
                            
                            // Chercher si on a des données pour ce jour
                            const dayStat = dailyStats.find(stat => stat.date === dateStr);
                            dayDurations.push(dayStat ? dayStat.duration / 3600 : 0); // Convertir en heures
                        }
                        
                        // Graphique temps quotidien
                        new Chart(document.getElementById('dailyTimeChart'), {
                            type: 'bar',
                            data: {
                                labels: dayLabels,
                                datasets: [{
                                    label: 'Heures travaillées',
                                    data: dayDurations,
                                    backgroundColor: 'rgba(60, 141, 188, 0.6)',
                                    borderColor: 'rgba(60, 141, 188, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Heures'
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const value = context.raw || 0;
                                                return `${value.toFixed(2)} heures`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données pour les graphiques:', error);
                    
                    // Afficher des graphiques vides en cas d'erreur
                    new Chart(document.getElementById('projectTimeChart'), {
                        type: 'pie',
                        data: {
                            labels: ['Aucune donnée'],
                            datasets: [{
                                data: [1],
                                backgroundColor: ['rgba(201, 203, 207, 0.6)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                    
                    new Chart(document.getElementById('dailyTimeChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                            datasets: [{
                                label: 'Heures travaillées',
                                data: [0, 0, 0, 0, 0, 0, 0],
                                backgroundColor: 'rgba(60, 141, 188, 0.6)',
                                borderColor: 'rgba(60, 141, 188, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }
    });
</script>
@stop 