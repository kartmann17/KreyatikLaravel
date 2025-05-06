@extends('admin.layout')

@section('title', 'Projets et chronométrage')

@section('page_title', 'Projets en cours & Chronométrage')

@section('content_body')
    <!-- Timer Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chronométrage de projet</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="timerProject">Projet</label>
                                <select id="timerProject" class="form-control">
                                    <option value="" selected disabled>Sélectionner un projet</option>
                                    @foreach ($activeProjects as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }} ({{ $project->client->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="timerTask">Description</label>
                                <input type="text" id="timerTask" class="form-control" placeholder="Description de la tâche">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="timer-display">00:00:00</div>
                            <div class="timer-controls">
                                <button id="startTimer" class="btn btn-success">
                                    <i class="fas fa-play mr-1"></i> Démarrer
                                </button>
                                <button id="pauseTimer" class="btn btn-warning" disabled>
                                    <i class="fas fa-pause mr-1"></i> Pause
                                </button>
                                <button id="stopTimer" class="btn btn-danger" disabled>
                                    <i class="fas fa-stop mr-1"></i> Arrêter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="row mt-4">
        <div class="col-12">
            <h3 class="mb-3">Projets actifs</h3>
        </div>
        
        @foreach ($activeProjects as $project)
        <div class="col-md-4">
            <div class="card project-card">
                <div class="card-header bg-gradient-primary project-card-header">
                    <h5 class="d-flex justify-content-between align-items-center">
                        {{ $project->title }}
                        <span class="badge badge-light">{{ number_format($project->price) }} €</span>
                    </h5>
                    <p class="mb-0 opacity-80">{{ $project->client->name }}</p>
                </div>
                <div class="card-body project-card-body">
                    <div class="mb-3">
                        <p class="text-sm text-muted mb-1">Avancement global</p>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: {{ $project->progress }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-muted mt-1">
                            <span>{{ $project->progress }}%</span>
                            <span>Temps: {{ $project->formatted_total_time }}</span>
                        </div>
                    </div>
                    
                    <div class="border-top border-gray-100 pt-3 mb-3">
                        <h6 class="font-weight-bold">Tâches récentes</h6>
                        <ul class="pl-2 mt-2 mb-2">
                            @forelse ($project->tasks->take(3) as $task)
                            <li class="text-sm d-flex align-items-center">
                                @if($task->status == 'terminé')
                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                @elseif($task->status == 'en-cours')
                                    <i class="fas fa-clock text-warning mr-2"></i>
                                @else
                                    <i class="fas fa-circle text-muted mr-2"></i>
                                @endif
                                {{ Str::limit($task->title, 30) }}
                            </li>
                            @empty
                            <li class="text-sm text-muted">Aucune tâche enregistrée</li>
                            @endforelse
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button class="startProjectTimer btn btn-sm btn-outline-success" 
                            data-project-id="{{ $project->id }}" data-project-name="{{ $project->title }}">
                            <i class="fas fa-play mr-1"></i> Démarrer
                        </button>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye mr-1"></i> Détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Time Logs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activité récente</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Projet</th>
                                    <th>Description</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->project->title }}</td>
                                    <td>{{ $log->description ?: 'Aucune description' }}</td>
                                    <td>{{ $log->formatted_duration }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune activité récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour le chronomètre
    let timer;
    let isRunning = false;
    let isPaused = false;
    let startTime;
    let pausedTime = 0;
    let seconds = 0;
    let currentProjectId = null;
    
    // Éléments du DOM
    const timerDisplay = document.querySelector('.timer-display');
    const timerProject = document.getElementById('timerProject');
    const timerTask = document.getElementById('timerTask');
    const startTimerBtn = document.getElementById('startTimer');
    const pauseTimerBtn = document.getElementById('pauseTimer');
    const stopTimerBtn = document.getElementById('stopTimer');
    const startProjectTimerBtns = document.querySelectorAll('.startProjectTimer');
    
    // Formatage du temps (HH:MM:SS)
    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    
    // Mise à jour du chronomètre
    function updateTimer() {
        const currentTime = Date.now();
        const elapsedTime = Math.floor((currentTime - startTime) / 1000) + pausedTime;
        seconds = elapsedTime;
        timerDisplay.textContent = formatTime(seconds);
    }
    
    // Démarrage du chronomètre
    function startTimer() {
        if (!timerProject.value) {
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
            currentProjectId = timerProject.value;
            
            // Mise à jour des boutons
            startTimerBtn.disabled = true;
            pauseTimerBtn.disabled = false;
            stopTimerBtn.disabled = false;
            timerProject.disabled = true;
        } else if (isPaused) {
            startTime = Date.now();
            timer = setInterval(updateTimer, 1000);
            isPaused = false;
            
            // Mise à jour des boutons
            startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Démarrer';
            pauseTimerBtn.disabled = false;
        }
    }
    
    // Mise en pause du chronomètre
    function pauseTimer() {
        if (isRunning && !isPaused) {
            clearInterval(timer);
            pausedTime = seconds;
            isPaused = true;
            
            // Mise à jour des boutons
            startTimerBtn.disabled = false;
            startTimerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Reprendre';
            pauseTimerBtn.disabled = true;
        }
    }
    
    // Arrêt du chronomètre
    function stopTimer() {
        if (isRunning) {
            clearInterval(timer);
            
            // Enregistrement du temps
            if (seconds > 0) {
                saveTimeLog();
            }
            
            // Réinitialisation du chronomètre
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
            timerProject.disabled = false;
        }
    }
    
    // Enregistrement du temps en base de données
    function saveTimeLog() {
        const projectId = currentProjectId;
        const description = timerTask.value || 'Temps enregistré automatiquement';
        const duration = seconds;
        
        axios.post('{{ route("admin.time.log") }}', {
            project_id: projectId,
            duration: duration,
            description: description
        })
        .then(function (response) {
            if (response.data.success) {
                // Notification
                Swal.fire({
                    title: 'Succès',
                    text: 'Temps enregistré avec succès !',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Réinitialisation des champs
                timerTask.value = '';
                
                // Actualiser la page pour afficher le nouveau log
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(function (error) {
            console.error('Erreur lors de l\'enregistrement du temps :', error);
            Swal.fire({
                title: 'Erreur',
                text: 'Une erreur est survenue lors de l\'enregistrement du temps.',
                icon: 'error'
            });
        });
    }
    
    // Événements
    startTimerBtn.addEventListener('click', startTimer);
    pauseTimerBtn.addEventListener('click', pauseTimer);
    stopTimerBtn.addEventListener('click', stopTimer);
    
    // Pour démarrer le chronomètre depuis les cartes de projet
    startProjectTimerBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const projectId = this.dataset.projectId;
            const projectName = this.dataset.projectName;
            
            // Sélectionner le projet dans la liste déroulante
            timerProject.value = projectId;
            
            // Démarrer le chronomètre
            startTimer();
        });
    });
});
</script>
@stop 