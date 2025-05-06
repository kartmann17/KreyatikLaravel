@extends('admin.layout')

@section('title', 'Mon profil')

@section('page_title', 'Mon profil')

@section('content_body')
<div class="container-fluid">
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

    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('dist/img/avatar.png') }}" alt="Photo de profil">
                    </div>

                    <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                    <p class="text-muted text-center">
                        @if(auth()->user()->role == 'admin')
                            <span class="badge badge-danger"><i class="fas fa-user-shield mr-1"></i>Administrateur</span>
                        @elseif(auth()->user()->role == 'staff')
                            <span class="badge badge-info"><i class="fas fa-user-tie mr-1"></i>Staff</span>
                        @elseif(auth()->user()->role == 'client')
                            <span class="badge badge-success"><i class="fas fa-user mr-1"></i>Client</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ auth()->user()->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Date d'inscription</b> <a class="float-right">{{ auth()->user()->created_at->format('d/m/Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Dernière connexion</b> <a class="float-right">{{ auth()->user()->last_login_at ?? 'Jamais' }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Informations supplémentaires</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong><i class="fas fa-shield-alt mr-1"></i> Rôle</strong>
                    <p class="text-muted">
                        @if(auth()->user()->role == 'admin')
                            Administrateur (Accès complet au système)
                        @elseif(auth()->user()->role == 'staff')
                            Staff (Accès limité aux fonctionnalités administratives)
                        @elseif(auth()->user()->role == 'client')
                            Client (Accès à l'interface client uniquement)
                        @endif
                    </p>

                    <hr>
                    @if(auth()->user()->role == 'client' && auth()->user()->client)
                    <strong><i class="fas fa-building mr-1"></i> Client associé</strong>
                    <p class="text-muted">{{ auth()->user()->client->name }}</p>
                    <hr>
                    @endif

                    <strong><i class="fas fa-clock mr-1"></i> Activité récente</strong>
                    <p class="text-muted">
                        Dernière mise à jour: {{ auth()->user()->updated_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="edit-profile-tab" data-toggle="pill" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="true">
                                <i class="fas fa-user-edit mr-1"></i>Modifier mon profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="change-password-tab" data-toggle="pill" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">
                                <i class="fas fa-key mr-1"></i>Changer mon mot de passe
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="preferences-tab" data-toggle="pill" href="#preferences" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="fas fa-cog mr-1"></i>Préférences
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profile-tabContent">
                        <!-- Modifier mon profil -->
                        <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="name">Nom complet</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Changer mon mot de passe -->
                        <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                            <form action="{{ route('admin.profile.update-password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="current_password">Mot de passe actuel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-unlock"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                        @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Nouveau mot de passe</label>
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
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Le mot de passe doit comporter au moins 8 caractères.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirmation du nouveau mot de passe</label>
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key mr-1"></i>Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Préférences -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                            <form action="{{ route('admin.profile.update-preferences') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label>Notifications par email</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="notify_new_ticket" name="preferences[notify_new_ticket]" {{ auth()->user()->preferences['notify_new_ticket'] ?? true ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="notify_new_ticket">Nouveaux tickets</label>
                                    </div>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="notify_ticket_update" name="preferences[notify_ticket_update]" {{ auth()->user()->preferences['notify_ticket_update'] ?? true ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="notify_ticket_update">Mises à jour des tickets</label>
                                    </div>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="notify_new_project" name="preferences[notify_new_project]" {{ auth()->user()->preferences['notify_new_project'] ?? true ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="notify_new_project">Nouveaux projets</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="language">Langue</label>
                                    <select class="form-control" id="language" name="preferences[language]">
                                        <option value="fr" {{ (auth()->user()->preferences['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ (auth()->user()->preferences['language'] ?? 'fr') == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="timezone">Fuseau horaire</label>
                                    <select class="form-control select2" id="timezone" name="preferences[timezone]">
                                        <option value="Europe/Paris" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                        <option value="Europe/London" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="America/New_York" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="America/Los_Angeles" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i>Enregistrer les préférences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            
            <!-- Activité récente -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>Activité récente
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="timeline timeline-inverse p-3">
                        <!-- Contenu de l'activité récente à implémenter ultérieurement -->
                        <div>
                            <i class="fas fa-user bg-primary"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ now()->subDays(1)->format('d/m/Y H:i') }}</span>
                                <h3 class="timeline-header">Modification du profil</h3>
                                <div class="timeline-body">
                                    Vous avez mis à jour vos informations de profil.
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <i class="fas fa-lock bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ now()->subDays(7)->format('d/m/Y H:i') }}</span>
                                <h3 class="timeline-header">Changement de mot de passe</h3>
                                <div class="timeline-body">
                                    Vous avez changé votre mot de passe.
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <i class="fas fa-sign-in-alt bg-success"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ now()->subDays(14)->format('d/m/Y H:i') }}</span>
                                <h3 class="timeline-header">Connexion au système</h3>
                                <div class="timeline-body">
                                    Connexion réussie depuis l'adresse IP: 192.168.1.1
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
@endsection

@section('custom_js')
<script>
$(function () {
    // Initialisation de Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    // Gestion des onglets via URL hash
    var hash = window.location.hash;
    if (hash) {
        $('#profile-tabs a[href="' + hash + '"]').tab('show');
    }
    
    // Mise à jour de l'URL lors du changement d'onglet
    $('#profile-tabs a').on('click', function (e) {
        window.location.hash = $(this).attr('href');
    });
});
</script>
@endsection 