@extends('admin.layout')

@section('title', 'Plans Tarifaires')

@section('page_title', 'Plans Tarifaires')

@section('content_body')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Plans Tarifaires</h1>
        <a href="{{ route('admin.pricing-plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nouveau Plan
        </a>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Plans Tarifaires</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tags me-1"></i>
            Liste des plans tarifaires
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%">Ordre</th>
                        <th style="width: 20%">Nom</th>
                        <th style="width: 15%">Prix mensuel</th>
                        <th style="width: 15%">Prix annuel</th>
                        <th style="width: 15%">Statut</th>
                        <th style="width: 15%">Mis en avant</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pricingPlans as $plan)
                    <tr>
                        <td>{{ $plan->order }}</td>
                        <td>
                            {{ $plan->name }}
                            @if($plan->is_custom_plan)
                                <span class="badge bg-info ms-1">Sur mesure</span>
                            @endif
                        </td>
                        <td>{{ $plan->monthly_price }} €</td>
                        <td>{{ $plan->annual_price }} €</td>
                        <td>
                            @if($plan->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td>
                            @if($plan->is_highlighted)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i> {{ $plan->highlight_text ?: 'Oui' }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.pricing-plans.edit', $plan) }}" class="btn btn-sm btn-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.pricing-plans.force-delete', $plan) }}" 
                                   class="btn btn-sm btn-danger" 
                                   title="Supprimer" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plan ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-tags fa-3x mb-3 text-secondary"></i>
                                <p class="mb-2">Aucun plan tarifaire trouvé</p>
                                <a href="{{ route('admin.pricing-plans.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Créer un plan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $pricingPlans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 