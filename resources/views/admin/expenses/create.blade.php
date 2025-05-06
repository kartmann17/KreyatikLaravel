@extends('admin.layout')

@section('title', 'Ajouter une Dépense')

@section('page_title', 'Ajouter une Dépense')

@section('content_body')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Dépenses</a></li>
        <li class="breadcrumb-item active">Ajouter</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Formulaire d'ajout
        </div>
        <div class="card-body">
            <form action="{{ route('admin.expenses.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Montant (€) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expense_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <div class="input-group">
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">Sélectionner une catégorie</option>
                                    <optgroup label="Développement">
                                        <option value="Hébergement" {{ old('category') == 'Hébergement' ? 'selected' : '' }}>Hébergement</option>
                                        <option value="Nom de domaine" {{ old('category') == 'Nom de domaine' ? 'selected' : '' }}>Nom de domaine</option>
                                        <option value="Logiciels" {{ old('category') == 'Logiciels' ? 'selected' : '' }}>Logiciels</option>
                                        <option value="Extensions/Plugins" {{ old('category') == 'Extensions/Plugins' ? 'selected' : '' }}>Extensions/Plugins</option>
                                        <option value="API & Services" {{ old('category') == 'API & Services' ? 'selected' : '' }}>API & Services</option>
                                        <option value="Freelance" {{ old('category') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    </optgroup>
                                    <optgroup label="Matériel">
                                        <option value="Matériel informatique" {{ old('category') == 'Matériel informatique' ? 'selected' : '' }}>Matériel informatique</option>
                                        <option value="Périphériques" {{ old('category') == 'Périphériques' ? 'selected' : '' }}>Périphériques</option>
                                        <option value="Bureau/Setup" {{ old('category') == 'Bureau/Setup' ? 'selected' : '' }}>Bureau/Setup</option>
                                    </optgroup>
                                    <optgroup label="Formations">
                                        <option value="Cours & Formations" {{ old('category') == 'Cours & Formations' ? 'selected' : '' }}>Cours & Formations</option>
                                        <option value="Livres" {{ old('category') == 'Livres' ? 'selected' : '' }}>Livres</option>
                                        <option value="Conférence" {{ old('category') == 'Conférence' ? 'selected' : '' }}>Conférence</option>
                                    </optgroup>
                                    <optgroup label="Bureautique">
                                        <option value="Internet" {{ old('category') == 'Internet' ? 'selected' : '' }}>Internet</option>
                                        <option value="Téléphone" {{ old('category') == 'Téléphone' ? 'selected' : '' }}>Téléphone</option>
                                        <option value="Espace de travail" {{ old('category') == 'Espace de travail' ? 'selected' : '' }}>Espace de travail</option>
                                    </optgroup>
                                    <optgroup label="Autres">
                                        <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Comptabilité" {{ old('category') == 'Comptabilité' ? 'selected' : '' }}>Comptabilité</option>
                                        <option value="Assurance pro" {{ old('category') == 'Assurance pro' ? 'selected' : '' }}>Assurance pro</option>
                                        <option value="Déplacements" {{ old('category') == 'Déplacements' ? 'selected' : '' }}>Déplacements</option>
                                        <option value="Autres" {{ old('category') == 'Autres' ? 'selected' : '' }}>Autres</option>
                                    </optgroup>
                                </select>
                                <input type="text" class="form-control @error('custom_category') is-invalid @enderror" id="custom_category" placeholder="Autre catégorie" style="display: none;">
                                <button class="btn btn-outline-secondary" type="button" id="toggle-custom-category">+</button>
                            </div>
                            @error('category')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de dépense <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="one_time" {{ old('type') == 'one_time' ? 'selected' : '' }}>Ponctuelle</option>
                                <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                                <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Annuelle</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="is_recurring" class="form-label">Récurrence</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_recurring">Dépense récurrente</label>
                            </div>
                            <small class="form-text text-muted">Cochez cette case si cette dépense se répète régulièrement</small>
                            @error('is_recurring')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary me-md-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-check recurring checkbox for monthly and annual expenses
        const typeSelect = document.getElementById('type');
        const recurringCheckbox = document.getElementById('is_recurring');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'monthly' || this.value === 'annual') {
                recurringCheckbox.checked = true;
            } else {
                recurringCheckbox.checked = false;
            }
        });
        
        // Gestion de la catégorie personnalisée
        const categorySelect = document.getElementById('category');
        const customCategoryInput = document.getElementById('custom_category');
        const toggleButton = document.getElementById('toggle-custom-category');
        
        let customMode = false;
        
        toggleButton.addEventListener('click', function() {
            customMode = !customMode;
            
            if (customMode) {
                categorySelect.style.display = 'none';
                customCategoryInput.style.display = 'block';
                toggleButton.textContent = '−';
                customCategoryInput.name = 'category';
                categorySelect.name = '';
            } else {
                categorySelect.style.display = 'block';
                customCategoryInput.style.display = 'none';
                toggleButton.textContent = '+';
                categorySelect.name = 'category';
                customCategoryInput.name = '';
            }
        });
    });
</script>
@endsection 