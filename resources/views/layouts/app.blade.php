<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! seo($SEOData ?? null) !!}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Macondo&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/ico" href="images/favicon/favicon.ico" />

    @vite('resources/css/app.css');
    <title>@yield('title', config('app.name'))</title>
</head>

<body class="h-full">
    <div class="site-wrapper">
        @auth
        @if(auth()->user()->role === 'client')
        <x-sidebar />
        <div class="site-content ml-0 lg:ml-64 transition-all duration-300">
            @yield('content')
        </div>
        @else
        @include('components.nav')
        <div class="site-content">
            @yield('content')
        </div>
        @endif
        @else
        @include('components.nav')
        <div class="site-content">
            @yield('content')
        </div>
        @endauth

        <footer class="footer {{ auth()->check() && auth()->user()->role === 'client' ? 'ml-0 lg:ml-64' : '' }} transition-all duration-300">
            <div class="container footer-container column-layout">
                <div class="footer-col branding text-center">
                    <h5>Kréyatik Studio</h5>
                    <p>
                        Création de sites sur-mesure, performants et beaux.<br>
                        Basé en France, au service de votre présence digitale.
                    </p>

                    <ul class="footer-links mt-3">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="/NosOffres">Nos Offres</a></li>
                        <li><a href="/Portfolio">Portfolio</a></li>
                        <li><a href="/Contact">Contact</a></li>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">CGV</a></li>
                        <li><a href="#">Confidentialité</a></li>
                    </ul>
                </div>
            </div>

            <p class="footer-bottom text-center">&copy; 2025 Kréyatik Studio. Tous droits réservés.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>