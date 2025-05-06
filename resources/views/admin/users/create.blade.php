@extends('admin.layout')

@section('title', 'Créer un nouvel utilisateur')

@section('page_title', 'Créer un nouvel utilisateur')

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

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Erreur !</h5>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2"></i>Informations de l'utilisateur
                    </h3>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Entrez le nom complet">
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
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="exemple@domaine.com">
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
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="client" {{ (old('role') == 'client' || old('role') == null) ? 'selected' : '' }}>Client</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group" id="client-section" style="{{ old('role') && old('role') != 'client' ? 'display: none;' : '' }}">
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
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                            <label for="password">Mot de passe</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
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
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="send_credentials" name="send_credentials" {{ old('send_credentials') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="send_credentials">Envoyer les identifiants par email</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus mr-1"></i>Créer l'utilisateur
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
                        <i class="fas fa-info-circle mr-2"></i>Informations
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-user-shield"></i> Administrateur</h5>
                        <p>Les administrateurs ont accès à toutes les fonctionnalités du système et peuvent gérer tous les aspects du site.</p>
                    </div>
                    
                    <div class="callout callout-info">
                        <h5><i class="fas fa-user-tie"></i> Staff</h5>
                        <p>Les membres du staff peuvent gérer les projets, les tickets et le contenu, mais ont des accès restreints à certaines fonctionnalités administratives.</p>
                    </div>
                    
                    <div class="callout callout-info">
                        <h5><i class="fas fa-user"></i> Client</h5>
                        <p>Les utilisateurs clients peuvent accéder uniquement à l'interface client, consulter leurs projets et créer des tickets.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <p class="mb-0">Assurez-vous de choisir un mot de passe sécurisé d'au moins 8 caractères.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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