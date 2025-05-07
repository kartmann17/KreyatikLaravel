@extends('admin.layout')

@section('title', 'Messages de contact')

@section('page_title', 'Messages de contact')

@section('content_body')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item active">Messages de contact</li>
            </ol>
        </div>
        <div>
            @if($unreadCount > 0)
                <form action="{{ route('admin.contact-messages.mark-multiple-as-read') }}" method="POST" id="mark-all-form" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" value="{{ $messages->whereIn('is_read', [false])->pluck('id')->implode(',') }}">
                    <button type="submit" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-check-double"></i> Marquer tous comme lus
                    </button>
                </form>
            @endif
        </div>
    </div>

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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-envelope me-1"></i>
                Messages de contact
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }} non lu(s)</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($messages->isEmpty())
                <div class="alert alert-info">
                    Aucun message de contact à afficher.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ $message->is_read ? '' : 'table-primary' }}">
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->name }}</td>
                                    <td>
                                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    </td>
                                    <td>{{ $message->subject }}</td>
                                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($message->is_read)
                                            <span class="badge bg-success">Lu</span>
                                        @else
                                            <span class="badge bg-warning">Non lu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-danger delete-message"
                                                data-id="{{ $message->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce message ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la suppression
        const deleteButtons = document.querySelectorAll('.delete-message');
        const deleteForm = document.getElementById('delete-form');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-id');
                deleteForm.action = "{{ url('admin/contact-messages') }}/" + messageId;
            });
        });

        // Ajouter l'événement de clic sur le bouton de confirmation de suppression
        confirmDeleteBtn.addEventListener('click', function() {
            deleteForm.submit();
        });
    });
</script>
@endsection