@extends('client.layout')

@section('title', 'Détail du ticket #' . $ticket->id)

@section('page_title', 'Ticket #' . $ticket->id . ' - ' . $ticket->title)

@section('content_body')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Retour aux tickets</span>
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
        <div class="flex">
            <div class="py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Informations sur le ticket -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->title }}</h2>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-3">Ouvert le {{ $ticket->created_at->format('d/m/Y à H:i') }}</span>
                        @php
                            $statusClasses = [
                                'open' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'closed' => 'bg-gray-100 text-gray-800',
                                'resolved' => 'bg-blue-100 text-blue-800'
                            ];
                            $priorityClasses = [
                                'basse' => 'bg-blue-100 text-blue-800',
                                'moyenne' => 'bg-yellow-100 text-yellow-800',
                                'haute' => 'bg-orange-100 text-orange-800',
                                'urgente' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ __('tickets.status.'.$ticket->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">Créé le {{ $ticket->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">Dernière activité {{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                            <span class="text-sm text-gray-600">Priorité: <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityClasses[$ticket->priority] ?? 'bg-gray-100 text-gray-800' }}">{{ __('tickets.priority.'.$ticket->priority) }}</span></span>
                        </div>
                        @if($ticket->project)
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm text-gray-600">Projet: <a href="{{ route('client.projects.show', $ticket->project->id) }}" class="text-blue-600 hover:text-blue-800">{{ $ticket->project->title }}</a></span>
                        </div>
                        @endif
                    </div>

                    <div class="prose max-w-none">
                        <p class="whitespace-pre-line text-gray-700">{{ $ticket->description }}</p>
                    </div>

                    @if($ticket->attachment)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="text-base font-medium text-gray-800 mb-2">Pièce jointe</h3>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ basename($ticket->attachment) }}</span>
                            </div>
                            <a href="{{ asset('storage/'.$ticket->attachment) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 ml-4">
                                Télécharger
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Réponses -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Réponses ({{ $ticket->replies ? count($ticket->replies) : 0 }})</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($ticket->replies ?? [] as $reply)
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-700 font-medium">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">{{ $reply->user->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $reply->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $reply->user->role == 'admin' || $reply->user->role == 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $reply->user->role == 'admin' || $reply->user->role == 'staff' ? 'Support' : 'Client' }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-700 whitespace-pre-line">
                                    {{ $reply->message }}
                                </div>
                                @if($reply->attachment)
                                <div class="mt-3 flex items-center p-2 bg-gray-50 rounded-lg border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <div class="flex-1 truncate">
                                        <span class="text-xs font-medium text-gray-900">{{ basename($reply->attachment) }}</span>
                                    </div>
                                    <a href="{{ asset('storage/'.$reply->attachment) }}" target="_blank" class="text-xs font-medium text-blue-600 hover:text-blue-800 ml-2">
                                        Télécharger
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="mt-2 text-gray-500">Aucune réponse pour le moment</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Formulaire de réponse -->
            @if($ticket->status != 'closed')
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Ajouter une réponse</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('client.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Votre message</label>
                            <textarea id="message" name="message" rows="5" required
                                class="block w-full px-3 py-2 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="reply_attachment" class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe (optionnel)</label>
                            <input type="file" id="reply_attachment" name="attachment" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-800">Informations</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3">
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">ID du ticket:</span>
                            <span class="text-sm font-medium">{{ $ticket->id }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Statut:</span>
                            <span class="text-sm px-2 py-0.5 rounded-full {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('tickets.status.'.$ticket->status) }}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Priorité:</span>
                            <span class="text-sm px-2 py-0.5 rounded-full {{ $priorityClasses[$ticket->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('tickets.priority.'.$ticket->priority) }}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Créé par:</span>
                            <span class="text-sm font-medium">{{ $ticket->user->name }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Date de création:</span>
                            <span class="text-sm font-medium">{{ $ticket->created_at->format('d/m/Y') }}</span>
                        </li>
                        @if($ticket->assigned_to)
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Assigné à:</span>
                            <span class="text-sm font-medium">{{ $ticket->assignedTo->name }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($ticket->status != 'closed')
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-800">Actions</h3>
                </div>
                <div class="p-4">
                    <button type="button" onclick="document.getElementById('close-modal').classList.remove('hidden')" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clôturer le ticket
                    </button>
                    
                    <!-- Modal de confirmation de fermeture -->
                    <div id="close-modal" class="fixed inset-0 z-10 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('close-modal').classList.add('hidden')"></div>
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
                                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('close-modal').classList.add('hidden')">Annuler</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($relatedTickets) && $relatedTickets && $relatedTickets->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-800">Tickets liés</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @foreach($relatedTickets as $relatedTicket)
                            <li>
                                <a href="{{ route('client.tickets.show', $relatedTicket->id) }}" class="flex items-center p-2 rounded hover:bg-gray-50">
                                    <div class="w-2 h-2 rounded-full {{ $statusClasses[$relatedTicket->status] ?? 'bg-gray-300' }} mr-3"></div>
                                    <div class="flex-1 truncate">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $relatedTicket->title }}</p>
                                        <p class="text-xs text-gray-500">#{{ $relatedTicket->id }} - {{ $relatedTicket->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fichier joint pour réponse
    const fileInput = document.getElementById('reply_attachment');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
            const fileNameDisplay = document.createElement('span');
            fileNameDisplay.textContent = fileName;
            fileNameDisplay.classList.add('ml-2', 'text-sm', 'text-gray-600');
            
            const existingDisplay = this.parentNode.querySelector('span');
            if (existingDisplay) {
                existingDisplay.remove();
            }
            
            this.parentNode.appendChild(fileNameDisplay);
        });
    }
});
</script>
@endsection 