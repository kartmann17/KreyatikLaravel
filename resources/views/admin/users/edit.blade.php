@extends('admin.layout')

@section('title', 'Modifier un utilisateur')

@section('page_title', 'Modifier un utilisateur')

@section('content_body')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Messages de notification -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès !</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur !</h5>
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i>Informations de l'utilisateur
                    </h3>
                </div>
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                </div>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group" id="client-section" style="{{ $user->role != 'client' ? 'display: none;' : '' }}">
                            <label for="client_id">Client associé</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </span>
                                </div>
                                <select class="form-control select2 @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                    <option value="">Sélectionnez un client (optionnel)</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $user->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmation du mot de passe</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Informations complémentaires
                    </h3>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('dist/img/avatar.png') }}" alt="Photo de profil">
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">
                        @if($user->role == 'admin')
                            <span class="badge badge-danger"><i class="fas fa-user-shield mr-1"></i>Administrateur</span>
                        @elseif($user->role == 'staff')
                            <span class="badge badge-info"><i class="fas fa-user-tie mr-1"></i>Staff</span>
                        @elseif($user->role == 'client')
                            <span class="badge badge-success"><i class="fas fa-user mr-1"></i>Client</span>
                        @endif
                    </p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Date d'inscription</b> <a class="float-right">{{ $user->created_at->format('d/m/Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Dernière mise à jour</b> <a class="float-right">{{ $user->updated_at->format('d/m/Y H:i') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Dernière connexion</b> <a class="float-right">{{ $user->last_login_at ?? 'Jamais' }}</a>
                        </li>
                    </ul>
                    @if(auth()->id() !== $user->id)
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete-modal">
                        <i class="fas fa-trash mr-1"></i>Supprimer cet utilisateur
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
@if(auth()->id() !== $user->id)
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Supprimer l'utilisateur
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?</p>
                <p class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i> Cette action est irréversible.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('custom_js')
<script>
$(function() {
    // Initialisation de Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    // Affichage conditionnel du champ client selon le rôle
    $('#role').on('change', function() {
        if ($(this).val() === 'client') {
            $('#client-section').show();
        } else {
            $('#client-section').hide();
        }
    });
});
</script>
@endsection 