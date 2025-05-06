@extends('admin.layout')

@section('title', 'Tableau de bord')

@section('page_title', 'Tableau de bord')

@section('content_body')
    <!-- Dashboard Widgets -->
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-gradient-primary card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-white">Gains (Mensuel)</h5>
                        <i class="fas fa-euro-sign text-white opacity-50 fa-2x"></i>
                    </div>
                    <p class="h2 text-white">{{ number_format($stats['monthlyEarnings'] ?? 0) }} €</p>
                    <p class="text-white opacity-75 mt-2 small">
                        <i class="fas fa-arrow-up mr-1"></i> Revenus du mois en cours
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-success card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-white">Dépenses (Mensuel)</h5>
                        <i class="fas fa-chart-line text-white opacity-50 fa-2x"></i>
                    </div>
                    <p class="h2 text-white">{{ number_format($stats['expenses'] ?? 0) }} €</p>
                    <p class="text-white opacity-75 mt-2 small">
                        <i class="fas fa-arrow-up mr-1"></i> Dépenses du mois en cours
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-warning card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-white">Tâches Terminées</h5>
                        <i class="fas fa-tasks text-white opacity-50 fa-2x"></i>
                    </div>
                    @php
                        $completedTasksPercentage = $stats['tasks'] > 0 ? round(($stats['completedTasks'] / $stats['tasks']) * 100) : 0;
                    @endphp
                    <p class="h2 text-white">{{ $completedTasksPercentage }}%</p>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $completedTasksPercentage }}%" aria-valuenow="{{ $completedTasksPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="d-flex align-items-center">
                    <div class="bg-primary p-3 rounded-circle mr-3">
                        <i class="fas fa-project-diagram text-white stats-icon"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Projets</h5>
                        <div class="d-flex align-items-center">
                            <span class="stats-number mr-3">{{ $stats['projects'] ?? 0 }}</span>
                            <div class="small">
                                <span class="text-success">{{ $stats['activeProjects'] ?? 0 }} actifs</span><br>
                                <span class="text-muted">{{ ($stats['projects'] ?? 0) - ($stats['activeProjects'] ?? 0) }} autres</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="d-flex align-items-center">
                    <div class="bg-success p-3 rounded-circle mr-3">
                        <i class="fas fa-users text-white stats-icon"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Clients</h5>
                        <div class="d-flex align-items-center">
                            <span class="stats-number mr-3">{{ $stats['clientCount'] ?? 0 }}</span>
                            <div class="small">
                                <span class="text-success">Clients</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="d-flex align-items-center">
                    <div class="bg-purple p-3 rounded-circle mr-3">
                        <i class="fas fa-money-bill-wave text-white stats-icon"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Messages</h5>
                        <span class="stats-number">{{ $stats['unreadMessages'] ?? 0 }}</span>
                        <p class="small text-muted">Non lus</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="d-flex align-items-center">
                    <div class="bg-danger p-3 rounded-circle mr-3">
                        <i class="fas fa-clock text-white stats-icon"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Tâches</h5>
                        <span class="stats-number">{{ $stats['tasks'] ?? 0 }}</span>
                        <p class="small text-muted">Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième ligne - Derniers messages et projets -->
    <div class="row mt-4">
        <!-- Derniers messages de contact -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-envelope mr-1"></i>
                        Derniers messages de contact
                        @if(isset($stats['unreadMessages']) && $stats['unreadMessages'] > 0)
                            <span class="badge badge-danger ml-2">{{ $stats['unreadMessages'] }} non lu(s)</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-tool">
                            <i class="fas fa-list"></i> Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($stats['recentMessages']) && count($stats['recentMessages']) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($stats['recentMessages'] as $message)
                                <li class="list-group-item notification-item {{ $message->is_read ? '' : 'unread' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $message->name }}</strong>
                                            @if(!$message->is_read)
                                                <span class="badge badge-warning">Nouveau</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $message->subject }}</small>
                                        </div>
                                        <div class="text-right">
                                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                            <br>
                                            <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted">
                            Aucun message de contact récent.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Projets récents -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-project-diagram mr-1"></i> Projets récents</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-tool">
                            <i class="fas fa-list"></i> Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($stats['recentProjects']) && count($stats['recentProjects']) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($stats['recentProjects'] as $project)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('admin.projects.show', $project->id) }}">
                                                <strong>{{ $project->name }}</strong>
                                            </a>
                                            <br>
                                            <span class="badge badge-{{ $project->status_color }}">{{ $project->status_label }}</span>
                                        </div>
                                        <div class="text-right">
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                     style="width: {{ $project->completion_percentage }}%"
                                                     aria-valuenow="{{ $project->completion_percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ $project->completion_percentage }}% terminé</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted">
                            Aucun projet récent.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
    // Watch for new messages
    checkUnreadMessages();
    @endif
});
</script>
@endsection