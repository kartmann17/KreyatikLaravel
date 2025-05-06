<nav class="navbar">
    <div class="flex justify-between items-center w-full px-4 text-white lg:justify-center">
        <a href="/" class="block lg:hidden">
            <img src="{{ asset('images/STUDIOcolibri.png') }}" alt="Logo Kréyatik" width="100" height="50" class="object-contain">
        </a>


        <div id="menuToggle" class="block lg:hidden cursor-pointer z-50">
            <i class="fas fa-bars text-white text-xl"></i>
        </div>

        <!-- Navigation desktop uniquement -->
        <ul class="hidden lg:flex gap-6 items-center justify-center mx-auto navbar-nav" id="navMenu">
            <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Accueil</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('NosOffres') ? 'active' : '' }}" href="/NosOffres">Nos Offres</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('Portfolio') ? 'active' : '' }}" href="/Portfolio">Portfolio</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('Contact') ? 'active' : '' }}" href="/Contact">Contact</a></li>

            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                    <li class="nav-item"><a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @elseif(auth()->user()->isClient())
                    <li class="nav-item"><a class="nav-link {{ request()->is('client/dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">Dashboard</a></li>
                @endif
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-link bg-transparent border-0" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion</button>
                    </form>
                </li>
            @else
                <li class="nav-item"><a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Connexion</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Inscription</a></li>
            @endauth
        </ul>
    </div>

    <!-- Menu mobile -->
    <div id="mobileMenu" class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-90 z-40 hidden">
        <div class="flex flex-col items-center justify-center h-full">
            <ul class="text-center">
                <li class="my-4">
                    <a href="/" class="text-white text-xl hover:text-yellow-400">Accueil</a>
                </li>
                <li class="my-4">
                    <a href="/NosOffres" class="text-white text-xl hover:text-yellow-400">Nos Offres</a>
                </li>
                <li class="my-4">
                    <a href="/Portfolio" class="text-white text-xl hover:text-yellow-400">Portfolio</a>
                </li>
                <li class="my-4">
                    <a href="/Contact" class="text-white text-xl hover:text-yellow-400">Contact</a>
                </li>

                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                    <li class="my-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-white text-xl hover:text-yellow-400">Dashboard</a>
                    </li>
                    @elseif(auth()->user()->isClient())
                    <li class="my-4">
                        <a href="{{ route('client.dashboard') }}" class="text-white text-xl hover:text-yellow-400">Dashboard</a>
                    </li>
                    @endif
                    <li class="my-4">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-white text-xl hover:text-yellow-400 bg-transparent border-0">Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="my-4">
                        <a href="{{ route('login') }}" class="text-white text-xl hover:text-yellow-400">Connexion</a>
                    </li>
                    <li class="my-4">
                        <a href="{{ route('register') }}" class="text-white text-xl hover:text-yellow-400">Inscription</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
