@extends('client.layout')

@section('title', 'Tableau de bord client')

@section('page_title', 'Tableau de bord client')

@section('content_body')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Projets -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-blue-100">PROJETS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['projectCount'] }}</h2>
                    </div>
                    <div class="ml-4 bg-white/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.projects.index') }}" class="flex justify-between items-center text-white text-sm font-medium group hover:underline">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Projets actifs -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-green-100">PROJETS ACTIFS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['activeProjects'] }}</h2>
                    </div>
                    <div class="ml-4 bg-white/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.projects.index') }}" class="flex justify-between items-center text-white text-sm font-medium group hover:underline">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tickets -->
        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-cyan-100">TICKETS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['ticketCount'] }}</h2>
                    </div>
                    <div class="ml-4 bg-white/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index') }}" class="flex justify-between items-center text-white text-sm font-medium group hover:underline">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tickets ouverts -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider mb-1 text-amber-100">TICKETS OUVERTS</h6>
                        <h2 class="text-3xl font-bold text-white">{{ $stats['openTickets'] }}</h2>
                    </div>
                    <div class="ml-4 bg-white/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('client.tickets.index') }}" class="flex justify-between items-center text-white text-sm font-medium group hover:underline">
                        <span>Voir les détails</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Projets récents -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Projets récents</h2>
                <a href="{{ route('client.projects.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                    Voir tous
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="p-5">
                @forelse($recentProjects as $project)
                    <a href="{{ route('client.projects.show', $project->id) }}" class="block mb-3 last:mb-0 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-gray-800">{{ $project->title }}</h3>
                            <span class="ml-2 px-2.5 py-0.5 text-xs font-medium {{ $project->status === 'en-cours' ? 'bg-green-100 text-green-800' : ($project->status === 'terminé' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }} rounded-full">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ Str::limit($project->description, 100) }}</p>
                        <div class="mt-2 text-xs text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $project->created_at->format('d/m/Y') }}
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <div class="bg-gray-50 inline-flex p-4 rounded-full mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Aucun projet récent</p>
                        <a href="{{ route('client.projects.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                            Voir tous les projets
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tickets récents -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Tickets récents</h2>
                <a href="{{ route('client.tickets.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                    Voir tous
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="p-5">
                @forelse($recentTickets as $ticket)
                    <a href="{{ route('client.tickets.show', $ticket->id) }}" class="block mb-3 last:mb-0 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-gray-800">{{ $ticket->title }}</h3>
                            <span class="ml-2 px-2.5 py-0.5 text-xs font-medium {{ $ticket->status === 'ouvert' ? 'bg-red-100 text-red-800' : ($ticket->status === 'en-cours' ? 'bg-amber-100 text-amber-800' : ($ticket->status === 'résolu' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }} rounded-full">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ Str::limit($ticket->description, 100) }}</p>
                        <div class="mt-2 text-xs text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $ticket->created_at->format('d/m/Y') }}
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <div class="bg-gray-50 inline-flex p-4 rounded-full mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Aucun ticket récent</p>
                        <a href="{{ route('client.tickets.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                            Voir tous les tickets
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toute logique JavaScript spécifique au dashboard client peut être ajoutée ici
});
</script>
@endsection 