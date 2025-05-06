@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détail de la Dépense</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Dépenses</a></li>
        <li class="breadcrumb-item active">{{ $expense->title }}</li>
    </ol>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-money-bill me-1"></i>
                        Informations de la Dépense
                    </div>
                    <div>
                        <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px">Titre</th>
                                <td>{{ $expense->title }}</td>
                            </tr>
                            <tr>
                                <th>Montant</th>
                                <td class="text-end fw-bold">{{ number_format($expense->amount, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : 'Non définie' }}</td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td>{{ $expense->category ?? 'Non catégorisé' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    @if ($expense->type == 'one_time')
                                        <span class="badge bg-secondary">Ponctuel</span>
                                    @elseif ($expense->type == 'monthly')
                                        <span class="badge bg-primary">Mensuel</span>
                                    @elseif ($expense->type == 'annual')
                                        <span class="badge bg-info">Annuel</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Récurrent</th>
                                <td>
                                    @if ($expense->is_recurring)
                                        <span class="text-success"><i class="fas fa-check me-1"></i> Oui</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times me-1"></i> Non</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $expense->description ?? 'Aucune description fournie' }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>
                                    @if ($expense->notes)
                                        <div class="notes-content">
                                            {!! nl2br(e($expense->notes)) !!}
                                        </div>
                                    @else
                                        <em class="text-muted">Aucune note</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le</th>
                                <td>{{ $expense->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Dernière modification</th>
                                <td>{{ $expense->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Dépenses similaires
                </div>
                <div class="card-body">
                    @if (count($similarExpenses) > 0)
                        <div class="list-group">
                            @foreach ($similarExpenses as $similar)
                                <a href="{{ route('admin.expenses.show', $similar->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $similar->title }}</h6>
                                        <small>{{ $similar->expense_date ? $similar->expense_date->format('d/m/Y') : 'Non définie' }}</small>
                                    </div>
                                    <p class="mb-1 text-end fw-bold">{{ number_format($similar->amount, 2, ',', ' ') }} €</p>
                                    @if ($similar->category)
                                        <small class="text-muted">{{ $similar->category }}</small>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">Aucune dépense similaire trouvée</p>
                    @endif
                </div>
            </div>

            @if ($expense->type != 'one_time')
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Occurrences de cette dépense
                </div>
                <div class="card-body">
                    @if (count($relatedExpenses) > 0)
                        <div class="list-group">
                            @foreach ($relatedExpenses as $related)
                                <a href="{{ route('admin.expenses.show', $related->id) }}" class="list-group-item list-group-item-action {{ $related->id == $expense->id ? 'active' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $related->expense_date ? $related->expense_date->format('M Y') : 'Non définie' }}</h6>
                                        <small>{{ $related->expense_date ? $related->expense_date->format('d/m/Y') : 'Non définie' }}</small>
                                    </div>
                                    <p class="mb-1 text-end fw-bold">{{ number_format($related->amount, 2, ',', ' ') }} €</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">Aucune occurrence associée trouvée</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .notes-content {
        white-space: pre-line;
    }
</style>
@endsection 