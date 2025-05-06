@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Modifier une Dépense</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Dépenses</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Modifier Dépense
        </div>
        <div class="card-body">
            <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $expense->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Montant (€) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" required>
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
                            <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $expense->category) }}" list="category-list">
                            <datalist id="category-list">
                                <option value="Loyer">Loyer</option>
                                <option value="Factures">Factures</option>
                                <option value="Alimentation">Alimentation</option>
                                <option value="Transport">Transport</option>
                                <option value="Santé">Santé</option>
                                <option value="Éducation">Éducation</option>
                                <option value="Divertissement">Divertissement</option>
                                <option value="Services">Services</option>
                                <option value="Abonnements">Abonnements</option>
                                <option value="Équipement">Équipement</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Taxes">Taxes</option>
                                <option value="Autre">Autre</option>
                            </datalist>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="one_time" {{ old('type', $expense->type) == 'one_time' ? 'selected' : '' }}>Ponctuel</option>
                                <option value="monthly" {{ old('type', $expense->type) == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                                <option value="annual" {{ old('type', $expense->type) == 'annual' ? 'selected' : '' }}>Annuel</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input @error('is_recurring') is-invalid @enderror" type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring', $expense->is_recurring) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_recurring">
                                    Dépense récurrente
                                </label>
                                @error('is_recurring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $expense->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logique pour afficher ou masquer des champs selon le type de dépense
    const typeSelect = document.getElementById('type');
    const isRecurringCheck = document.getElementById('is_recurring');
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'monthly' || this.value === 'annual') {
            isRecurringCheck.checked = true;
        }
    });
});
</script>
@endsection 