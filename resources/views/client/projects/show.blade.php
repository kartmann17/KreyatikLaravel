@extends('client.layout')

@section('title', $project->name)

@section('page_title', $project->name)

@section('content_body')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Tableau de bord
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('client.projects.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Projets</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $project->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Détails du projet</h2>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $project->status == 'en attente' ? 'bg-gray-100 text-gray-800' : ($project->status == 'en cours' ? 'bg-blue-100 text-blue-800' : ($project->status == 'terminé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $project->name }}</h2>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $progress }}% terminé</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h6 class="text-sm font-medium text-gray-500 mb-1">Date de début</h6>
                            <p class="text-gray-800">{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}</p>
                        </div>
                        <div>
                            <h6 class="text-sm font-medium text-gray-500 mb-1">Date de fin</h6>
                            <p class="text-gray-800">{{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h6 class="text-sm font-medium text-gray-500 mb-1">Description</h6>
                        <p class="text-gray-800">{{ $project->description ?: 'Aucune description disponible.' }}</p>
                    </div>

                    @if($project->notes)
                    <div class="mb-4">
                        <h6 class="text-sm font-medium text-gray-500 mb-1">Notes</h6>
                        <p class="text-gray-800">{{ $project->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Tâches ({{ count($tasks) }})</h2>
                </div>
                <div class="p-6">
                    @if($tasks->isEmpty())
                        <div class="text-center py-8">
                            <div class="mx-auto bg-gray-100 rounded-full p-3 w-16 h-16 flex items-center justify-center mb-4">
                                <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-medium text-gray-700 mb-1">Aucune tâche disponible</h5>
                            <p class="text-gray-500 text-sm">Aucune tâche n'a encore été créée pour ce projet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $task->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($task->is_completed)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminée</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En cours</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($task->priority == 'basse')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Basse</span>
                                                @elseif($task->priority == 'moyenne')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Moyenne</span>
                                                @elseif($task->priority == 'haute')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Haute</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Non définie' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Équipe du projet</h2>
                </div>
                <div class="p-6">
                    @if(isset($project->assignedUsers) && count($project->assignedUsers) > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($project->assignedUsers as $user)
                                <li class="py-3 flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center font-medium mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->role }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto bg-gray-100 rounded-full p-3 w-16 h-16 flex items-center justify-center mb-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-medium text-gray-700 mb-1">Aucun membre assigné</h5>
                            <p class="text-gray-500 text-sm">Aucun membre n'a encore été assigné à ce projet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Statistiques</h2>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h6 class="text-sm font-medium text-gray-500 mb-1">Tâches terminées</h6>
                        <p class="text-gray-800 font-medium">{{ $tasks->where('is_completed', true)->count() }} / {{ $tasks->count() }}</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-sm font-medium text-gray-500 mb-1">Jours restants</h6>
                        <p class="font-medium {{ $project->end_date && $project->end_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                            @if($project->end_date && $project->end_date->isFuture())
                                {{ now()->diffInDays($project->end_date) }} jours
                            @elseif($project->end_date && $project->end_date->isPast())
                                Dépassé de {{ $project->end_date->diffInDays(now()) }} jours
                            @else
                                Non défini
                            @endif
                        </p>
                    </div>
                    @if($project->budget)
                    <div>
                        <h6 class="text-sm font-medium text-gray-500 mb-1">Budget</h6>
                        <p class="text-gray-800 font-medium">{{ number_format($project->budget, 2, ',', ' ') }} €</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Support</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Vous avez des questions ou besoin d'aide concernant ce projet?</p>
                    <a href="{{ route('client.tickets.create', ['project_id' => $project->id]) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-ticket-alt mr-2"></i> Créer un nouveau ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 