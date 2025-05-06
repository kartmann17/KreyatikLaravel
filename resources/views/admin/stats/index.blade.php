@extends('admin.layout')

@section('title', 'Statistiques')

@section('page_title', 'Statistiques et Rapports')

@section('content_body')
    <!-- Filtres et génération de rapports -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Générer un rapport personnalisé</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stats.report') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report_start_date">Date de début</label>
                                <input type="date" class="form-control" id="report_start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report_end_date">Date de fin</label>
                                <input type="date" class="form-control" id="report_end_date" name="end_date" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="report_type">Type de rapport</label>
                                <select class="form-control" id="report_type" name="type" required>
                                    <option value="revenue">Revenus</option>
                                    <option value="tasks">Tâches</option>
                                    <option value="time">Temps passé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group mb-0 w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-chart-line mr-1"></i> Générer le rapport
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenus mensuels -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Revenus mensuels</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="monthlyRevenueChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Revenus par source</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="revenueBySourceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tâches et Temps -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tâches par statut</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="tasksByStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Temps passé par projet (Top 5)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="timeByProjectChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom_js')
<script>
    $(function() {
        // Initialiser les dates du rapport avec le mois courant
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        document.getElementById('report_start_date').value = firstDay.toISOString().slice(0, 10);
        document.getElementById('report_end_date').value = lastDay.toISOString().slice(0, 10);
        
        // Revenus mensuels - Line Chart
        const monthlyRevenueData = {
            labels: {!! json_encode($monthlyRevenue['labels']) !!},
            datasets: [{
                label: 'Revenus (€)',
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderColor: 'rgba(60,141,188,1)',
                pointBackgroundColor: 'rgba(60,141,188,1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(60,141,188,1)',
                data: {!! json_encode($monthlyRevenue['data']) !!}
            }]
        };
        
        const monthlyRevenueOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        
        new Chart(document.getElementById('monthlyRevenueChart').getContext('2d'), {
            type: 'line',
            data: monthlyRevenueData,
            options: monthlyRevenueOptions
        });
        
        // Revenus par source - Pie Chart
        const revenueBySourceData = {
            labels: {!! json_encode($revenueBySource['labels']) !!},
            datasets: [{
                data: {!! json_encode($revenueBySource['data']) !!},
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'
                ],
            }]
        };
        
        const revenueBySourceOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };
        
        new Chart(document.getElementById('revenueBySourceChart').getContext('2d'), {
            type: 'pie',
            data: revenueBySourceData,
            options: revenueBySourceOptions
        });
        
        // Tâches par statut - Doughnut Chart
        const tasksByStatusData = {
            labels: {!! json_encode($tasksByStatus['labels']) !!},
            datasets: [{
                data: {!! json_encode($tasksByStatus['data']) !!},
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12'
                ],
            }]
        };
        
        const tasksByStatusOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };
        
        new Chart(document.getElementById('tasksByStatusChart').getContext('2d'), {
            type: 'doughnut',
            data: tasksByStatusData,
            options: tasksByStatusOptions
        });
        
        // Temps par projet - Bar Chart
        const timeByProjectData = {
            labels: {!! json_encode($timeByProject['labels']) !!},
            datasets: [{
                label: 'Heures',
                backgroundColor: 'rgba(60,141,188,0.8)',
                borderColor: 'rgba(60,141,188,1)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {!! json_encode($timeByProject['data']) !!}
            }]
        };
        
        const timeByProjectOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        
        new Chart(document.getElementById('timeByProjectChart').getContext('2d'), {
            type: 'bar',
            data: timeByProjectData,
            options: timeByProjectOptions
        });
    });
</script>
@stop 