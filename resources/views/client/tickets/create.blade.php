@extends('client.layout')

@section('title', 'Créer un ticket de support')

@section('page_title', 'Créer un nouveau ticket')

@section('content_body')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Retour à la liste des tickets</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Formulaire de demande d'assistance</h2>
            <p class="text-sm text-gray-600">Veuillez fournir autant de détails que possible pour nous aider à résoudre votre problème rapidement.</p>
        </div>
        
        <div class="p-5">
            @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-base font-medium">Veuillez corriger les erreurs suivantes :</p>
                </div>
                <ul class="ml-7 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('client.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre du ticket <span class="text-red-600">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="block w-full px-3 py-2 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Projet concerné</label>
                        <div class="relative">
                            <select id="project_id" name="project_id" 
                                class="block w-full px-3 py-2 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 appearance-none">
                                <option value="">Aucun projet spécifique</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <select id="priority" name="priority" required
                                class="block w-full px-3 py-2 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 appearance-none">
                                <option value="basse" {{ old('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="moyenne" {{ old('priority') == 'moyenne' || !old('priority') ? 'selected' : '' }}>Moyenne</option>
                                <option value="haute" {{ old('priority') == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description détaillée <span class="text-red-600">*</span></label>
                        <textarea id="description" name="description" rows="6" required
                            class="block w-full px-3 py-2 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Veuillez décrire votre problème en détail. Incluez les étapes pour reproduire le problème et toute information pertinente.</p>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe (optionnel)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Télécharger un fichierou</span>
                                        <input id="attachment" name="attachment" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">glissez-déposez</p>
                                </div>
                                <p class="text-xs text-gray-500">Images, documents, archives (max 2MB)</p>
                            </div>
                        </div>
                        @error('attachment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Soumettre le ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-800">Conseils pour un traitement rapide</h3>
            </div>
        </div>
        
        <div class="p-5">
            <ul class="space-y-3 text-gray-700">
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Choisissez un titre descriptif et concis qui résume clairement votre problème</span>
                </li>
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Incluez les étapes précises pour reproduire le problème afin que notre équipe puisse le recréer</span>
                </li>
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Ajoutez des captures d'écran ou vidéos si possible pour illustrer le problème visuellement</span>
                </li>
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Si le problème est lié à un projet spécifique, sélectionnez-le dans la liste pour un meilleur contexte</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection 