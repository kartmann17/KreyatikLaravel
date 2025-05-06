@extends('admin.layout')

@section('title', 'Créer un nouveau ticket')

@section('page_title', 'Créer un nouveau ticket')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Créer un nouveau ticket</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">Créer</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informations du ticket</h3>
            </div>
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror">
                                    <option value="">Sélectionner un client (optionnel)</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_id">Projet</label>
                                <select name="project_id" id="project_id" class="form-control @error('project_id') is-invalid @enderror">
                                    <option value="">Sélectionner un projet (optionnel)</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->title }} ({{ $project->client->name ?? 'Sans client' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Titre du ticket</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="priority">Priorité</label>
                                <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                    <option value="basse" {{ old('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                                    <option value="moyenne" {{ old('priority', 'moyenne') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="haute" {{ old('priority') == 'haute' ? 'selected' : '' }}>Haute</option>
                                    <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                </select>
                                @error('priority')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Statut</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="ouvert" {{ old('status', 'ouvert') == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                                    <option value="en-cours" {{ old('status') == 'en-cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="résolu" {{ old('status') == 'résolu' ? 'selected' : '' }}>Résolu</option>
                                    <option value="fermé" {{ old('status') == 'fermé' ? 'selected' : '' }}>Fermé</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="assigned_to">Assigner à</label>
                                <select name="assigned_to" id="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror">
                                    <option value="">Non assigné</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Commentaire initial (optionnel)</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                        @error('comment')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_private" name="is_private" {{ old('is_private') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_private">Commentaire privé (visible uniquement par l'équipe)</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Créer le ticket</button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-default">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {
        // Script pour mettre à jour les projets en fonction du client sélectionné
        $('#client_id').change(function() {
            const clientId = $(this).val();
            const projectSelect = $('#project_id');
            
            // Réinitialiser la liste des projets
            projectSelect.empty();
            projectSelect.append('<option value="">Sélectionner un projet (optionnel)</option>');
            
            if (clientId) {
                // Filtrer les projets pour ce client
                @foreach($projects as $project)
                    if ('{{ $project->client_id }}' == clientId) {
                        projectSelect.append('<option value="{{ $project->id }}">{{ $project->title }}</option>');
                    }
                @endforeach
            } else {
                // Afficher tous les projets
                @foreach($projects as $project)
                    projectSelect.append('<option value="{{ $project->id }}">{{ $project->title }} ({{ $project->client->name ?? "Sans client" }})</option>');
                @endforeach
            }
        });
    });
</script>
@endsection 