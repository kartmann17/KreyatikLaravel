<div class="sidebar-client bg-white shadow-lg fixed left-0 top-0 bottom-0 w-64 overflow-y-auto z-10 transform transition-transform duration-300 ease-in-out" id="sidebar">
    <div class="p-6 border-b">
        <div class="flex items-center justify-between">
            <a href="/" class="flex items-center">
                <img src="{{ asset('images/STUDIOcolibri.png') }}" alt="Logo Kréyatik" class="h-10 w-auto object-contain">
            </a>
            <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-800 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="py-6">
        <div class="px-6 mb-8">
            <p class="text-xs uppercase text-gray-500 font-semibold tracking-wider mb-3">Navigation</p>
            <a href="{{ route('client.dashboard') }}" class="flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('client.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Tableau de bord
            </a>
            <a href="{{ route('client.projects.index') }}" class="flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('client.projects.*') ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Mes projets
            </a>
            <a href="{{ route('client.tickets.index') }}" class="flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('client.tickets.*') ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                Mes tickets
            </a>
        </div>

        <div class="px-6 mb-8">
            <p class="text-xs uppercase text-gray-500 font-semibold tracking-wider mb-3">Actions rapides</p>
            <a href="{{ route('client.tickets.create') }}" class="flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Créer un ticket
            </a>
        </div>

        <div class="border-t px-6 pt-6 mt-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="font-medium">{{ auth()->user()->name }}</h5>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full py-3 px-4 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bouton pour ouvrir la sidebar sur mobile -->
<button id="openSidebar" class="fixed top-4 left-4 z-20 lg:hidden bg-white p-2 rounded-lg shadow-md focus:outline-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Overlay pour fermer la sidebar sur mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-0 hidden lg:hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // Fonction pour ouvrir la sidebar
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
        }

        // Fonction pour fermer la sidebar
        function closeSidebar() {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // En mobile par défaut, la sidebar est fermée
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }

        openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    });
</script>
