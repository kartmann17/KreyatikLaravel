@extends('admin.layout')

@section('title', 'Modifier un Plan Tarifaire')

@section('page_title', 'Modifier un Plan Tarifaire')

@section('content_body')
<div class="container-fluid px-4">
    <h1 class="mt-4">Modifier un Plan Tarifaire</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pricing-plans.index') }}">Plans Tarifaires</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Modification de "{{ $pricingPlan->name }}"
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pricing-plans.update', $pricingPlan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du plan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pricingPlan->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $pricingPlan->slug) }}">
                            <div class="form-text">Laissez vide pour générer automatiquement à partir du nom</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="monthly_price" class="form-label">Prix mensuel</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', $pricingPlan->monthly_price) }}">
                            </div>
                            @error('monthly_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="annual_price" class="form-label">Prix annuel</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control @error('annual_price') is-invalid @enderror" id="annual_price" name="annual_price" value="{{ old('annual_price', $pricingPlan->annual_price) }}">
                            </div>
                            @error('annual_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="intro_text" class="form-label">Texte d'introduction</label>
                            <input type="text" class="form-control @error('intro_text') is-invalid @enderror" id="intro_text" name="intro_text" value="{{ old('intro_text', $pricingPlan->intro_text) }}">
                            @error('intro_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="button_text" class="form-label">Texte du bouton</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $pricingPlan->button_text) }}">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="features" class="form-label">Caractéristiques</label>
                    <textarea class="form-control @error('features') is-invalid @enderror" id="features" name="features" rows="5">{{ old('features', is_array($pricingPlan->features) ? implode("\n", $pricingPlan->features) : $pricingPlan->features) }}</textarea>
                    <div class="form-text">Entrez une fonctionnalité par ligne</div>
                    @error('features')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_highlighted" name="is_highlighted" value="1" {{ old('is_highlighted', $pricingPlan->is_highlighted) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_highlighted">Mettre en avant ce plan</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3" id="highlight_text_container" style="{{ old('is_highlighted', $pricingPlan->is_highlighted) ? '' : 'display: none;' }}">
                            <label for="highlight_text" class="form-label">Texte de mise en avant</label>
                            <input type="text" class="form-control @error('highlight_text') is-invalid @enderror" id="highlight_text" name="highlight_text" value="{{ old('highlight_text', $pricingPlan->highlight_text) }}">
                            @error('highlight_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $pricingPlan->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activer ce plan</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_custom_plan" name="is_custom_plan" value="1" {{ old('is_custom_plan', $pricingPlan->is_custom_plan) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_custom_plan">Plan sur mesure</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3" id="custom_plan_text_container" style="{{ old('is_custom_plan', $pricingPlan->is_custom_plan) ? '' : 'display: none;' }}">
                            <label for="custom_plan_text" class="form-label">Texte du plan sur mesure</label>
                            <input type="text" class="form-control @error('custom_plan_text') is-invalid @enderror" id="custom_plan_text" name="custom_plan_text" value="{{ old('custom_plan_text', $pricingPlan->custom_plan_text) }}">
                            @error('custom_plan_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Ordre d'affichage</label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $pricingPlan->order) }}" min="1" max="999">
                    <div class="form-text">Les plans sont affichés du plus petit nombre au plus grand</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pricing-plans.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-génération du slug
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        nameInput.addEventListener('input', function() {
            if (!slugInput.value) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        });

        // Afficher/masquer le texte de mise en avant
        const isHighlightedCheckbox = document.getElementById('is_highlighted');
        const highlightTextContainer = document.getElementById('highlight_text_container');
        
        isHighlightedCheckbox.addEventListener('change', function() {
            highlightTextContainer.style.display = this.checked ? 'block' : 'none';
        });

        // Afficher/masquer le texte du plan sur mesure
        const isCustomPlanCheckbox = document.getElementById('is_custom_plan');
        const customPlanTextContainer = document.getElementById('custom_plan_text_container');
        
        isCustomPlanCheckbox.addEventListener('change', function() {
            customPlanTextContainer.style.display = this.checked ? 'block' : 'none';
        });
    });
</script>
@endsection 