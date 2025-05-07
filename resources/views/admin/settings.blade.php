@extends('admin.layout')

@section('title', 'Paramètres')

@section('page_title', 'Paramètres SEO du site')

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <!-- Paramètres du compte utilisateur -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Paramètres du compte</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}" id="accountSettingsForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ auth()->user()->name }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ auth()->user()->email }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer votre mot de passe.</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Mettre à jour le compte</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Paramètres SEO du site -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Paramètres SEO du site</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.seo') }}" id="seoForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_name">Nom du site</label>
                                <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ config('app.name') }}">
                                @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Nom principal de votre site web.</small>
                            </div>

                            <div class="form-group">
                                <label for="default_description">Description par défaut</label>
                                <textarea class="form-control @error('default_description') is-invalid @enderror" id="default_description" name="default_description" rows="3">{{ config('seo.default_description', '') }}</textarea>
                                @error('default_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Cette description sera utilisée si aucune description spécifique n'est définie pour une page.</small>
                            </div>

                            <div class="form-group">
                                <label for="default_keywords">Mots-clés par défaut</label>
                                <input type="text" class="form-control @error('default_keywords') is-invalid @enderror" id="default_keywords" name="default_keywords" value="{{ config('seo.default_keywords', '') }}">
                                @error('default_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Séparés par des virgules.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_image">Image par défaut pour les partages</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('default_image') is-invalid @enderror" id="default_image" name="default_image">
                                        <label class="custom-file-label" for="default_image">Choisir un fichier</label>
                                    </div>
                                </div>
                                @error('default_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Image utilisée lors du partage sur les réseaux sociaux si aucune image spécifique n'est définie.</small>

                                @if(config('seo.default_image'))
                                <div class="mt-2">
                                    <img src="{{ asset(config('seo.default_image')) }}" alt="Image SEO par défaut" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="locale">Langue par défaut</label>
                                <select class="form-control @error('locale') is-invalid @enderror" id="locale" name="locale">
                                    <option value="fr_FR" {{ config('seo.locale') == 'fr_FR' ? 'selected' : '' }}>Français</option>
                                    <option value="en_US" {{ config('seo.locale') == 'en_US' ? 'selected' : '' }}>English (US)</option>
                                    <option value="en_GB" {{ config('seo.locale') == 'en_GB' ? 'selected' : '' }}>English (UK)</option>
                                </select>
                                @error('locale')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Réseaux sociaux</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                    </div>
                                    <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" name="social_facebook" placeholder="URL Facebook" value="{{ config('seo.social_facebook', '') }}">
                                    @error('social_facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    </div>
                                    <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" name="social_twitter" placeholder="URL Twitter" value="{{ config('seo.social_twitter', '') }}">
                                    @error('social_twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    </div>
                                    <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" name="social_instagram" placeholder="URL Instagram" value="{{ config('seo.social_instagram', '') }}">
                                    @error('social_instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                    </div>
                                    <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror" name="social_linkedin" placeholder="URL LinkedIn" value="{{ config('seo.social_linkedin', '') }}">
                                    @error('social_linkedin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Enregistrer les paramètres SEO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Paramètres SEO par page -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">SEO par page</h3>
            </div>
            <div class="card-body">
                <p class="mb-3">Gérer le SEO spécifique pour chaque page principale du site.</p>

                <ul class="nav nav-tabs" id="pagesTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="offres-tab" data-toggle="tab" href="#offres" role="tab" aria-controls="offres" aria-selected="false">Nos Offres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Espace Client</a>
                    </li>
                </ul>

                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="pagesTabsContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form method="POST" action="{{ route('admin.settings.seo.page', ['page' => 'home']) }}" class="pageSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="home_title">Titre</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="home_title" name="title" value="{{ config('seo.pages.home.title', 'Accueil | ' . config('app.name')) }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="home_description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="home_description" name="description" rows="3">{{ config('seo.pages.home.description', '') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <form method="POST" action="{{ route('admin.settings.seo.page', ['page' => 'contact']) }}" class="pageSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="contact_title">Titre</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="contact_title" name="title" value="{{ config('seo.pages.contact.title', 'Contactez-nous | ' . config('app.name')) }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact_description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="contact_description" name="description" rows="3">{{ config('seo.pages.contact.description', 'Prenez contact avec notre équipe pour discuter de vos projets ou obtenir plus d\'informations sur nos services.') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="offres" role="tabpanel" aria-labelledby="offres-tab">
                        <form method="POST" action="{{ route('admin.settings.seo.page', ['page' => 'offres']) }}" class="pageSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="offres_title">Titre</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="offres_title" name="title" value="{{ config('seo.pages.offres.title', 'Nos Offres | ' . config('app.name')) }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="offres_description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="offres_description" name="description" rows="3">{{ config('seo.pages.offres.description', 'Découvrez nos offres et tarifs adaptés à vos besoins. Solutions sur mesure pour tous vos projets.') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                        <form method="POST" action="{{ route('admin.settings.seo.page', ['page' => 'client']) }}" class="pageSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="client_title">Titre</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="client_title" name="title" value="{{ config('seo.pages.client.title', 'Espace Client | ' . config('app.name')) }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="client_description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="client_description" name="description" rows="3">{{ config('seo.pages.client.description', 'Accédez à votre espace client pour gérer vos projets et suivre vos demandes en cours.') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Préférences utilisateur -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Préférences utilisateur</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.preferences') }}" id="preferencesForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="theme">Thème</label>
                                <select class="form-control @error('theme') is-invalid @enderror" id="theme" name="theme">
                                    <option value="light" {{ (auth()->user()->preferences['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Clair</option>
                                    <option value="dark" {{ (auth()->user()->preferences['theme'] ?? '') == 'dark' ? 'selected' : '' }}>Sombre</option>
                                    <option value="auto" {{ (auth()->user()->preferences['theme'] ?? '') == 'auto' ? 'selected' : '' }}>Automatique (selon système)</option>
                                </select>
                                @error('theme')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="language">Langue</label>
                                <select class="form-control @error('language') is-invalid @enderror" id="language" name="language">
                                    <option value="fr" {{ (auth()->user()->preferences['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ (auth()->user()->preferences['language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time_format">Format de l'heure</label>
                                <select class="form-control @error('time_format') is-invalid @enderror" id="time_format" name="time_format">
                                    <option value="12h" {{ (auth()->user()->preferences['time_format'] ?? '24h') == '12h' ? 'selected' : '' }}>12 heures (AM/PM)</option>
                                    <option value="24h" {{ (auth()->user()->preferences['time_format'] ?? '24h') == '24h' ? 'selected' : '' }}>24 heures</option>
                                </select>
                                @error('time_format')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Notifications</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" {{ (auth()->user()->preferences['notifications']['email'] ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="email_notifications">Recevoir des notifications par email</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input" id="browser_notifications" name="browser_notifications" {{ (auth()->user()->preferences['notifications']['browser'] ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="browser_notifications">Activer les notifications du navigateur</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Enregistrer les préférences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- À propos -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">À propos de l'application</h3>
            </div>
            <div class="card-body">
                <p><strong>Version :</strong> 1.0.0</p>
                <p><strong>Framework :</strong> Laravel {{ app()->version() }}</p>
                <p><strong>Développé par :</strong> Votre équipe</p>
                <p>Cette application de gestion de projets est conçue pour vous aider à organiser efficacement votre travail, suivre le temps passé sur vos tâches et gérer vos clients.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(function() {
        // Afficher le nom du fichier sélectionné
        $('#default_image').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choisir un fichier');
        });

        // Gestion des onglets dans l'URL
        let url = location.href.replace(/\/$/, "");
        if (location.hash) {
            const hash = url.split("#");
            $('#pagesTabs a[href="#' + hash[1] + '"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if (hash == "#home") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            newUrl += "/";
            history.replaceState(null, null, newUrl);
        });

        // Gestion des erreurs de validation
        @if($errors -> any())
        @if($errors -> has('name') || $errors -> has('email') || $errors - > has('current_password') || $errors - > has('password'))
        // Focus sur le formulaire de compte
        $('html, body').animate({
            scrollTop: $("#accountSettingsForm").offset().top - 100
        }, 200);
        @elseif($errors -> has('site_name') || $errors -> has('default_description') || $errors -> has('default_keywords') ||
            $errors -> has('default_image') || $errors -> has('locale') ||
            $errors -> has('social_facebook') || $errors -> has('social_twitter') ||
            $errors -> has('social_instagram') || $errors -> has('social_linkedin'))
        // Focus sur le formulaire SEO
        $('html, body').animate({
            scrollTop: $("#seoForm").offset().top - 100
        }, 200);
        @elseif($errors -> has('theme') || $errors -> has('language') || $errors -> has('time_format'))
        // Focus sur le formulaire de préférences
        $('html, body').animate({
            scrollTop: $("#preferencesForm").offset().top - 100
        }, 200);
        @endif
        @endif

        // Afficher des messages de succès
        @if(session('success'))
        Swal.fire({
            title: 'Succès!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
        @endif
    });
</script>
@endsection