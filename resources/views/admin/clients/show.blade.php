@extends('admin.layout')

@section('title', 'Détails du client')

@section('page_title', 'Détails du client')

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du client</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                        </a>
                        <a href="{{ route('admin.clients.edit', $client->id ?? 1) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Succès!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nom</span>
                                    <span class="info-box-number">{{ $client->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Entreprise</span>
                                    <span class="info-box-number">{{ $client->company ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-envelope"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Email</span>
                                    <span class="info-box-number">{{ $client->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-phone"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Téléphone</span>
                                    <span class="info-box-number">{{ $client->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Adresse</h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $client->address ?? 'Aucune adresse enregistrée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Notes</h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $client->notes ?? 'Aucune note enregistrée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Projets du client -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Projets du client</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Nouveau projet
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projects ?? [] as $project)
                                    <tr>
                                        <td>{{ $project->id ?? 'N/A' }}</td>
                                        <td>{{ $project->title ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($project->status))
                                                @if($project->status == 'en-cours')
                                                    <span class="badge badge-primary">En cours</span>
                                                @elseif($project->status == 'termine')
                                                    <span class="badge badge-success">Terminé</span>
                                                @elseif($project->status == 'en-attente')
                                                    <span class="badge badge-warning">En attente</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $project->status }}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ isset($project->start_date) ? date('d/m/Y', strtotime($project->start_date)) : 'N/A' }}</td>
                                        <td>{{ isset($project->end_date) ? date('d/m/Y', strtotime($project->end_date)) : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.projects.show', $project->id ?? 1) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.projects.edit', $project->id ?? 1) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun projet trouvé pour ce client</td>
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