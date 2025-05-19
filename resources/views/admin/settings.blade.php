@extends('admin.layout')

@section('title', 'Paramètres')

@section('page_title', 'Paramètres SEO du site')

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <!-- Paramètres SEO du site -->
        <div class="card">
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
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings->site_name }}">
                                <small class="form-text text-muted">Nom principal de votre site web.</small>
                            </div>

                            <div class="form-group">
                                <label for="default_description">Description par défaut</label>
                                <textarea class="form-control" id="default_description" name="default_description" rows="3">{{ $settings->default_description }}</textarea>
                                <small class="form-text text-muted">Cette description sera utilisée si aucune description spécifique n'est définie pour une page.</small>
                            </div>

                            <div class="form-group">
                                <label for="default_keywords">Mots-clés par défaut</label>
                                <input type="text" class="form-control" id="default_keywords" name="default_keywords" value="{{ $settings->default_keywords }}">
                                <small class="form-text text-muted">Séparés par des virgules.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_image">Image par défaut pour les partages</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="default_image" name="default_image">
                                        <label class="custom-file-label" for="default_image">Choisir un fichier</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Image utilisée lors du partage sur les réseaux sociaux si aucune image spécifique n'est définie.</small>

                                @if($settings->default_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->default_image) }}" alt="Image SEO par défaut" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="locale">Langue par défaut</label>
                                <select class="form-control" id="locale" name="locale">
                                    <option value="fr_FR" {{ $settings->locale == 'fr_FR' ? 'selected' : '' }}>Français</option>
                                    <option value="en_US" {{ $settings->locale == 'en_US' ? 'selected' : '' }}>English (US)</option>
                                    <option value="en_GB" {{ $settings->locale == 'en_GB' ? 'selected' : '' }}>English (UK)</option>
                                </select>
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
                                    <input type="url" class="form-control" name="social_facebook" placeholder="URL Facebook" value="{{ $settings->social_facebook }}">
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="social_twitter" placeholder="URL Twitter" value="{{ $settings->social_twitter }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="social_instagram" placeholder="URL Instagram" value="{{ $settings->social_instagram }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="social_linkedin" placeholder="URL LinkedIn" value="{{ $settings->social_linkedin }}">
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
                        <a class="nav-link" id="offres-tab" data-toggle="tab" href="#offres" role="tab" aria-controls="offres" aria-selected="false">Nos Offres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="portfolio-tab" data-toggle="tab" href="#portfolio" role="tab" aria-controls="portfolio" aria-selected="false">Portfolio</a>
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
                                <input type="text" class="form-control" id="home_title" name="title" value="{{ $pagesSeo['home']->title ?? config('seo.pages.home.title', 'Accueil | ' . config('app.name')) }}">
                            </div>

                            <div class="form-group">
                                <label for="home_description">Description</label>
                                <textarea class="form-control" id="home_description" name="description" rows="3">{{ $pagesSeo['home']->description ?? config('seo.pages.home.description', '') }}</textarea>
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
                                <input type="text" class="form-control" id="contact_title" name="title" value="{{ $pagesSeo['contact']->title ?? config('seo.pages.contact.title', 'Contactez-nous | ' . config('app.name')) }}">
                            </div>

                            <div class="form-group">
                                <label for="contact_description">Description</label>
                                <textarea class="form-control" id="contact_description" name="description" rows="3">{{ $pagesSeo['contact']->description ?? config('seo.pages.contact.description', '') }}</textarea>
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
                                <input type="text" class="form-control" id="offres_title" name="title" value="{{ $pagesSeo['offres']->title ?? config('seo.pages.offres.title', 'Nos Offres | ' . config('app.name')) }}">
                            </div>

                            <div class="form-group">
                                <label for="offres_description">Description</label>
                                <textarea class="form-control" id="offres_description" name="description" rows="3">{{ $pagesSeo['offres']->description ?? config('seo.pages.offres.description', '') }}</textarea>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="portfolio" role="tabpanel" aria-labelledby="portfolio-tab">
                        <form method="POST" action="{{ route('admin.settings.seo.page', ['page' => 'portfolio']) }}" class="pageSettingsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="portfolio_title">Titre</label>
                                <input type="text" class="form-control" id="portfolio_title" name="title" value="{{ $pagesSeo['portfolio']->title ?? config('seo.pages.portfolio.title', 'Portfolio | ' . config('app.name')) }}">
                            </div>

                            <div class="form-group">
                                <label for="portfolio_description">Description</label>
                                <textarea class="form-control" id="portfolio_description" name="description" rows="3">{{ $pagesSeo['portfolio']->description ?? config('seo.pages.portfolio.description', '') }}</textarea>
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
                                <input type="text" class="form-control" id="client_title" name="title" value="{{ $pagesSeo['client']->title ?? config('seo.pages.client.title', 'Espace Client | ' . config('app.name')) }}">
                            </div>

                            <div class="form-group">
                                <label for="client_description">Description</label>
                                <textarea class="form-control" id="client_description" name="description" rows="3">{{ $pagesSeo['client']->description ?? config('seo.pages.client.description', '') }}</textarea>
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

<!-- À propos -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">À propos de l'application</h3>
            </div>
            <div class="card-body">
                <p><strong>Version :</strong> 1.0.0</p>
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
        // Gestion des messages flash
        @if(session('success'))
        Swal.fire({
            title: 'Succès!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Erreur!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
        @endif

        // Afficher le nom du fichier sélectionné
        $('#default_image').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choisir un fichier');
        });
    });
</script>
@endsection