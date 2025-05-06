@extends('admin.layout')

@section('title', 'Gestion des Dépenses')

@section('page_title', 'Gestion des Dépenses')

@section('content_body')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Dépenses</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Dépenses</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Liste des dépenses
            </div>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle"></i> Ajouter une dépense
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="category-filter" class="form-label">Catégorie</label>
                    <select id="category-filter" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type-filter" class="form-label">Type</label>
                    <select id="type-filter" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="one_time">Ponctuelle</option>
                        <option value="monthly">Mensuelle</option>
                        <option value="annual">Annuelle</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date-start" class="form-label">Date début</label>
                    <input type="date" id="date-start" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="date-end" class="form-label">Date fin</label>
                    <input type="date" id="date-end" class="form-control">
                </div>
            </div>

            <div class="table-responsive">
                <table id="expensesTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Montant (€)</th>
                            <th>Date</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Récurrente</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->title }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2, ',', ' ') }} €</td>
                            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                            <td>{{ $expense->category }}</td>
                            <td>
                                @if($expense->type == 'one_time')
                                    <span class="badge bg-secondary">Ponctuelle</span>
                                @elseif($expense->type == 'monthly')
                                    <span class="badge bg-info">Mensuelle</span>
                                @elseif($expense->type == 'annual')
                                    <span class="badge bg-primary">Annuelle</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($expense->is_recurring)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-expense" 
                                            data-id="{{ $expense->id }}" 
                                            data-title="{{ $expense->title }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="text-end">{{ number_format($expenses->sum('amount'), 2, ',', ' ') }} €</th>
                            <th colspan="5"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer la dépense <span id="expense-title" class="fw-bold"></span> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable initialization with jQuery
        $(document).ready(function() {
            const table = $('#expensesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
                },
                responsive: true,
                order: [[2, 'desc']], // Order by date column descending
                pageLength: 25,
                columnDefs: [
                    { orderable: false, targets: 6 } // Disable sorting on actions column
                ]
            });

            // Category filter
            $('#category-filter').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // Type filter
            $('#type-filter').on('change', function() {
                const value = this.value;
                
                table.column(4).search(value === 'one_time' ? 'Ponctuelle' : 
                                      value === 'monthly' ? 'Mensuelle' : 
                                      value === 'annual' ? 'Annuelle' : '').draw();
            });

            // Date range filter
            const dateStart = document.getElementById('date-start');
            const dateEnd = document.getElementById('date-end');

            function formatDate(date) {
                const parts = date.split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }

            // Custom date range filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const startDateValue = dateStart.value;
                const endDateValue = dateEnd.value;
                
                if (!startDateValue && !endDateValue) {
                    return true;
                }
                
                const dateValue = data[2].split('/').reverse().join('-');
                
                let startDate = startDateValue ? new Date(startDateValue) : null;
                let endDate = endDateValue ? new Date(endDateValue) : null;
                let date = new Date(dateValue);
                
                if ((!startDate || date >= startDate) && (!endDate || date <= endDate)) {
                    return true;
                }
                
                return false;
            });

            $(dateStart).on('change', function() { table.draw(); });
            $(dateEnd).on('change', function() { table.draw(); });

            // Delete expense modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteButtons = document.querySelectorAll('.delete-expense');
            const deleteForm = document.getElementById('delete-form');
            const expenseTitle = document.getElementById('expense-title');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    
                    deleteForm.action = `{{ url('admin/expenses') }}/${id}`;
                    expenseTitle.textContent = title;
                    deleteModal.show();
                });
            });
        });
    });
</script>
@endsection 