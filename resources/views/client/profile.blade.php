@extends('client.layout')

@section('title', 'Mon profil')

@section('page_title', 'Mon profil')

@section('content_body')
<div class="container mx-auto py-6 px-4">
    <!-- Messages de notification -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p class="font-bold">Succès !</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Erreur !</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-1/3">
            <!-- Profile Image -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-center">
                        <img class="h-32 w-32 rounded-full mx-auto" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="Photo de profil">
                    </div>

                    <h3 class="text-xl font-semibold text-center mt-4">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Client
                        </span>
                    </p>

                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Email</span>
                            <span class="text-gray-900">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-t border-gray-100">
                            <span class="text-gray-500">Inscription</span>
                            <span class="text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-t border-gray-100">
                            <span class="text-gray-500">Dernière connexion</span>
                            <span class="text-gray-900">{{ auth()->user()->last_login_at ?? 'Jamais' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="bg-blue-50 px-4 py-2 border-b border-blue-100">
                    <h3 class="text-lg font-medium text-blue-900">Informations complémentaires</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rôle</h4>
                        <p class="mt-1 text-gray-900">Client (Accès à l'interface client uniquement)</p>
                    </div>

                    @if(auth()->user()->client)
                    <div class="pt-4 border-t border-gray-200 mb-4">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Entreprise associée</h4>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->client->name }}</p>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Activité récente</h4>
                        <p class="mt-1 text-gray-900">
                            Dernière mise à jour: {{ auth()->user()->updated_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="md:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <a href="#edit-profile" class="edit-profile-tab border-blue-500 text-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" aria-current="page">
                            <i class="fas fa-user-edit mr-2"></i>Modifier mon profil
                        </a>
                        <a href="#change-password" class="change-password-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fas fa-key mr-2"></i>Changer mon mot de passe
                        </a>
                        <a href="#preferences" class="preferences-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fas fa-cog mr-2"></i>Préférences
                        </a>
                    </nav>
                </div>
                
                <div class="p-6">
                    <div class="tab-content">
                        <!-- Modifier mon profil -->
                        <div id="edit-profile-tab" class="active">
                            <form action="{{ route('client.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Changer mon mot de passe -->
                        <div id="change-password-tab" class="hidden">
                            <form action="{{ route('client.profile.update-password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-unlock text-gray-400"></i>
                                        </div>
                                        <input type="password" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('current_password') border-red-300 text-red-900 placeholder-red-300 @enderror" id="current_password" name="current_password" required>
                                    </div>
                                    @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 @enderror" id="password" name="password" required>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>Le mot de passe doit comporter au moins 8 caractères.
                                    </p>
                                    @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation du nouveau mot de passe</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-key mr-2"></i>Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Préférences -->
                        <div id="preferences-tab" class="hidden">
                            <form action="{{ route('client.profile.update-preferences') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notifications par email</h3>
                                    
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="notify_new_ticket" name="preferences[notify_new_ticket]" {{ auth()->user()->preferences['notify_new_ticket'] ?? true ? 'checked' : '' }}>
                                        <label for="notify_new_ticket" class="ml-2 block text-sm text-gray-900">
                                            Nouveaux tickets
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="notify_ticket_update" name="preferences[notify_ticket_update]" {{ auth()->user()->preferences['notify_ticket_update'] ?? true ? 'checked' : '' }}>
                                        <label for="notify_ticket_update" class="ml-2 block text-sm text-gray-900">
                                            Mises à jour des tickets
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="notify_new_project" name="preferences[notify_new_project]" {{ auth()->user()->preferences['notify_new_project'] ?? true ? 'checked' : '' }}>
                                        <label for="notify_new_project" class="ml-2 block text-sm text-gray-900">
                                            Nouveaux projets
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="language" class="block text-sm font-medium text-gray-700">Langue</label>
                                    <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" id="language" name="preferences[language]">
                                        <option value="fr" {{ (auth()->user()->preferences['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ (auth()->user()->preferences['language'] ?? 'fr') == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="timezone" class="block text-sm font-medium text-gray-700">Fuseau horaire</label>
                                    <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" id="timezone" name="preferences[timezone]">
                                        <option value="Europe/Paris" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                        <option value="Europe/London" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="America/New_York" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="America/Los_Angeles" {{ (auth()->user()->preferences['timezone'] ?? 'Europe/Paris') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                                    </select>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i>Enregistrer les préférences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activité récente -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-history mr-2"></i>Activité récente
                    </h3>
                </div>
                <div class="overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        <li class="p-4">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-edit h-6 w-6 rounded-full bg-blue-100 text-blue-500 p-1"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Modification du profil</p>
                                    <p class="text-sm text-gray-500">
                                        Vous avez mis à jour vos informations de profil.
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i>{{ now()->subDays(1)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="p-4">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-lock h-6 w-6 rounded-full bg-yellow-100 text-yellow-500 p-1"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Changement de mot de passe</p>
                                    <p class="text-sm text-gray-500">
                                        Vous avez changé votre mot de passe.
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i>{{ now()->subDays(7)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="p-4">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sign-in-alt h-6 w-6 rounded-full bg-green-100 text-green-500 p-1"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Connexion au système</p>
                                    <p class="text-sm text-gray-500">
                                        Connexion réussie depuis l'adresse IP: 192.168.1.1
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i>{{ now()->subDays(14)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabs = {
        'edit-profile': {
            tab: document.querySelector('.edit-profile-tab'),
            content: document.getElementById('edit-profile-tab')
        },
        'change-password': {
            tab: document.querySelector('.change-password-tab'),
            content: document.getElementById('change-password-tab')
        },
        'preferences': {
            tab: document.querySelector('.preferences-tab'),
            content: document.getElementById('preferences-tab')
        }
    };
    
    // Fonction pour afficher un onglet
    function showTab(tabId) {
        // Masquer tous les contenus d'onglets
        Object.values(tabs).forEach(item => {
            item.content.classList.add('hidden');
            item.tab.classList.remove('border-blue-500', 'text-blue-600');
            item.tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Afficher l'onglet sélectionné
        tabs[tabId].content.classList.remove('hidden');
        tabs[tabId].tab.classList.remove('border-transparent', 'text-gray-500');
        tabs[tabId].tab.classList.add('border-blue-500', 'text-blue-600');
    }
    
    // Gestion des clics sur les onglets
    Object.entries(tabs).forEach(([id, item]) => {
        item.tab.addEventListener('click', function(e) {
            e.preventDefault();
            showTab(id);
            window.location.hash = id;
        });
    });
    
    // Vérifier si un onglet est spécifié dans l'URL
    const hash = window.location.hash.substring(1);
    if (hash && tabs[hash]) {
        showTab(hash);
    }
});
</script>
@endsection