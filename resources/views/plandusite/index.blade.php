<x-header title="Plan du site" />

<section class="min-h-[80vh] bg-gradient-to-b from-gray-50 to-white py-16 flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800 relative">
                Plan du Site
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-primary rounded-full"></span>
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Section Principale -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800">Pages Principales</h2>
                    </div>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('welcome') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('nos-offres') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Nos Offres
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('portfolio') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Section Légale -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800">Informations Légales</h2>
                    </div>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('mentionslegales') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Mentions Légales
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('confidentialite') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Politique de Confidentialité
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('CGV') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                CGV
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Section Client -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800">Espace Client</h2>
                    </div>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Connexion
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Inscription
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.dashboard') }}" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors duration-200 group">
                                <span class="w-2 h-2 bg-primary rounded-full mr-3 transform group-hover:scale-150 transition-transform"></span>
                                Tableau de bord
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />