@extends('admin.layout')

@section('title', 'Rapport de statistiques')

@section('page_title', 'Rapport de statistiques')

@section('content_body')
    <!-- Entête du rapport -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if ($reportType == 'revenue')
                            Rapport des revenus
                        @elseif ($reportType == 'tasks')
                            Rapport des tâches
                        @elseif ($reportType == 'time')
                            Rapport du temps passé
                        @endif
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-secondary" onclick="window.print()">
                            <i class="fas fa-print mr-1"></i> Imprimer
                        </button>
                        <a href="{{ route('admin.stats') }}" class="btn btn-sm btn-primary ml-1">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Type de rapport:</strong> 
                                @if ($reportType == 'revenue')
                                    Revenus
                                @elseif ($reportType == 'tasks')
                                    Tâches
                                @elseif ($reportType == 'time')
                                    Temps passé
                                @endif
                            </p>
                            <p><strong>Période:</strong> {{ request()->start_date }} à {{ request()->end_date }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Généré le:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                            <p><strong>Par:</strong> {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu du rapport -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Résultats du rapport</h3>
                </div>
                <div class="card-body">
                    <!-- Affichage conditionnel selon le type de rapport -->
                    @if ($reportType == 'revenue')
                        <div class="chart mb-4">
                            <canvas id="reportRevenueChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Projet</th>
                                        <th>Client</th>
                                        <th>Montant (€)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Exemple de données - à remplacer par les vraies données -->
                                    <tr>
                                        <td>01/06/2023</td>
                                        <td>Site e-commerce</td>
                                        <td>Client A</td>
                                        <td>5,000.00</td>
                                    </tr>
                                    <tr>
                                        <td>15/06/2023</td>
                                        <td>Application mobile</td>
                                        <td>Client B</td>
                                        <td>8,500.00</td>
                                    </tr>
                                    <tr>
                                        <td>22/06/2023</td>
                                        <td>Refonte site web</td>
                                        <td>Client C</td>
                                        <td>3,200.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th>16,700.00 €</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @elseif ($reportType == 'tasks')
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="chart">
                                    <canvas id="taskStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart">
                                    <canvas id="taskPriorityChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Projet</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th>Progression</th>
                                        <th>Date d'échéance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Exemple de données - à remplacer par les vraies données -->
                                    <tr>
                                        <td>Créer maquette</td>
                                        <td>Site e-commerce</td>
                                        <td><span class="badge badge-success">Terminé</span></td>
                                        <td><span class="badge badge-info">Normal</span></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                            </div>
                                        </td>
                                        <td>15/06/2023</td>
                                    </tr>
                                    <tr>
                                        <td>Développer API</td>
                                        <td>Application mobile</td>
                                        <td><span class="badge badge-primary">En cours</span></td>
                                        <td><span class="badge badge-warning">Haute</span></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
                                            </div>
                                        </td>
                                        <td>30/06/2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @elseif ($reportType == 'time')
                        <div class="chart mb-4">
                            <canvas id="timeReportChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Projet</th>
                                        <th>Tâche</th>
                                        <th>Utilisateur</th>
                                        <th>Durée</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Exemple de données - à remplacer par les vraies données -->
                                    <tr>
                                        <td>12/06/2023</td>
                                        <td>Site e-commerce</td>
                                        <td>Créer maquette</td>
                                        <td>Jean Dupont</td>
                                        <td>3h 20m</td>
                                        <td>Création de maquettes pour la page d'accueil</td>
                                    </tr>
                                    <tr>
                                        <td>13/06/2023</td>
                                        <td>Application mobile</td>
                                        <td>Développer API</td>
                                        <td>Marie Martin</td>
                                        <td>5h 45m</td>
                                        <td>Développement des endpoints API pour l'authentification</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th>9h 05m</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function() {
        // Initialisation des graphiques en fonction du type de rapport
        
        @if ($reportType == 'revenue')
        // Exemple de graphique pour les revenus
        const revenueData = {
            labels: ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
            datasets: [{
                label: 'Revenus (€)',
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderColor: 'rgba(60,141,188,1)',
                pointBackgroundColor: 'rgba(60,141,188,1)',
                pointBorderColor: '#fff',
                data: [4500, 3800, 5200, 3200]
            }]
        };
        
        new Chart(document.getElementById('reportRevenueChart').getContext('2d'), {
            type: 'line',
            data: revenueData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        @elseif ($reportType == 'tasks')
        // Graphiques pour les tâches
        const taskStatusData = {
            labels: ['À faire', 'En cours', 'Terminées'],
            datasets: [{
                data: [5, 8, 12],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12']
            }]
        };
        
        new Chart(document.getElementById('taskStatusChart').getContext('2d'), {
            type: 'doughnut',
            data: taskStatusData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        const taskPriorityData = {
            labels: ['Basse', 'Normale', 'Haute', 'Urgente'],
            datasets: [{
                data: [3, 10, 8, 4],
                backgroundColor: ['#00c0ef', '#3c8dbc', '#f39c12', '#f56954']
            }]
        };
        
        new Chart(document.getElementById('taskPriorityChart').getContext('2d'), {
            type: 'pie',
            data: taskPriorityData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        @elseif ($reportType == 'time')
        // Graphique pour le temps passé
        const timeData = {
            labels: ['Site e-commerce', 'Application mobile', 'Refonte site web', 'Maintenance', 'Support'],
            datasets: [{
                label: 'Heures',
                backgroundColor: 'rgba(60,141,188,0.8)',
                borderColor: 'rgba(60,141,188,1)',
                data: [12, 19, 8, 5, 3]
            }]
        };
        
        new Chart(document.getElementById('timeReportChart').getContext('2d'), {
            type: 'bar',
            data: timeData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        @endif
    });
</script>
@stop 