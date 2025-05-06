@extends('client.layout')

@section('title', 'Mes tickets de support')

@section('page_title', 'Mes tickets de support')

@section('content_body')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    <!-- Statistiques des tickets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Tickets ouverts -->
        <div class="bg-blue-600 rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-blue-100">TICKETS OUVERTS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['open'] }}</h2>
                    </div>
                    <div class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index', ['status' => 'ouvert']) }}" class="flex justify-between items-center text-white text-sm font-medium group">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tickets en cours -->
        <div class="bg-amber-500 rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-amber-100">EN COURS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['in_progress'] }}</h2>
                    </div>
                    <div class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index', ['status' => 'en-cours']) }}" class="flex justify-between items-center text-white text-sm font-medium group">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tickets résolus -->
        <div class="bg-green-600 rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-green-100">RÉSOLUS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['resolved'] }}</h2>
                    </div>
                    <div class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index', ['status' => 'résolu']) }}" class="flex justify-between items-center text-white text-sm font-medium group">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Total des tickets -->
        <div class="bg-cyan-600 rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-cyan-100">TICKETS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index') }}" class="flex justify-between items-center text-white text-sm font-medium group">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des tickets -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200 px-5 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Mes tickets</h2>
            <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau ticket
            </a>
        </div>
        
        <div class="p-5">
            <!-- Filtres et recherche -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <form action="{{ route('client.tickets.index') }}" method="GET" class="flex flex-wrap gap-2">
                    <div class="flex-grow sm:flex-grow-0">
                        <input type="text" name="search" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    
                    <div>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="ouvert" {{ $status === 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                            <option value="en-cours" {{ $status === 'en-cours' ? 'selected' : '' }}>En cours</option>
                            <option value="résolu" {{ $status === 'résolu' ? 'selected' : '' }}>Résolu</option>
                            <option value="fermé" {{ $status === 'fermé' ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Filtrer
                    </button>
                </form>
            </div>
            
            <!-- Tableau des tickets -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière réponse</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->ticket_number }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                        {{ $ticket->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->project->title ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($ticket->status === 'ouvert')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Ouvert</span>
                                    @elseif ($ticket->status === 'en-cours')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">En cours</span>
                                    @elseif ($ticket->status === 'résolu')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Résolu</span>
                                    @elseif ($ticket->status === 'fermé')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Fermé</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($ticket->priority === 'basse')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Basse</span>
                                    @elseif ($ticket->priority === 'moyenne')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Moyenne</span>
                                    @elseif ($ticket->priority === 'haute')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Haute</span>
                                    @elseif ($ticket->priority === 'urgente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($ticket->lastComment)
                                        {{ $ticket->lastComment->created_at->diffForHumans() }}
                                        @if ($ticket->lastComment->user_id !== auth()->id())
                                            <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Support</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Aucune réponse</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}" class="inline-flex items-center px-2 py-1 border border-blue-600 text-blue-600 rounded text-xs font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                                    
                                    @if ($ticket->status !== 'fermé')
                                        <button type="button" class="inline-flex items-center px-2 py-1 border border-gray-400 text-gray-600 rounded text-xs font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="document.getElementById('close-modal-{{ $ticket->id }}').classList.remove('hidden')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Fermer
                                        </button>
                                        
                                        <!-- Modal de confirmation de fermeture -->
                                        <div id="close-modal-{{ $ticket->id }}" class="fixed inset-0 z-10 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('close-modal-{{ $ticket->id }}').classList.add('hidden')"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Fermer le ticket</h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir fermer ce ticket ? Cette action est irréversible.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <form action="{{ route('client.tickets.close', $ticket->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Fermer le ticket</button>
                                                        </form>
                                                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('close-modal-{{ $ticket->id }}').classList.add('hidden')">Annuler</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun ticket trouvé</h3>
                                        <p class="text-gray-500 mb-4">Il n'y a pas de tickets correspondant à vos critères de recherche.</p>
                                        <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Créer un ticket
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($tickets->hasPages())
                <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($tickets->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                                Précédent
                            </span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Précédent
                            </a>
                        @endif
                        
                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Suivant
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                                Suivant
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">{{ $tickets->firstItem() ?? 0 }}</span> à <span class="font-medium">{{ $tickets->lastItem() ?? 0 }}</span> sur <span class="font-medium">{{ $tickets->total() }}</span> résultats
                            </p>
                        </div>
                        <div>
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 