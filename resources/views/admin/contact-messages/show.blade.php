@extends('admin.layout')

@section('title', 'Détail du message')

@section('page_title', 'Détail du message')

@section('content_body')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contact-messages.index') }}">Messages de contact</a></li>
                <li class="breadcrumb-item active">Détail</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-envelope me-1"></i>
                Message de {{ $message->name }}
                @if($message->is_read)
                    <span class="badge bg-success ms-2">Lu</span>
                @else
                    <span class="badge bg-warning ms-2">Non lu</span>
                @endif
            </div>
            <div>
                <span class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Informations de contact</h5>
                    <p>
                        <strong>Nom :</strong> {{ $message->name }}<br>
                        <strong>Email :</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a><br>
                        <strong>IP :</strong> {{ $message->ip_address ?? 'Non disponible' }}<br>
                        <strong>Date :</strong> {{ $message->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Statut :</strong> 
                        @if($message->is_read)
                            <span class="badge bg-success">Lu le {{ $message->read_at->format('d/m/Y H:i') }}</span>
                        @else
                            <span class="badge bg-warning">Non lu</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Actions</h5>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" id="replyBtn" class="btn btn-primary">
                            <i class="fas fa-reply"></i> Répondre
                        </button>
                        
                        @if(!$message->is_read)
                            <form action="{{ route('admin.contact-messages.mark-as-read', $message->id) }}" method="POST" id="markAsReadForm">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Marquer comme lu
                                </button>
                            </form>
                        @endif
                        
                        <button type="button" id="deleteBtn" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <h5 class="border-bottom pb-2">Sujet</h5>
                    <p class="fw-bold">{{ $message->subject }}</p>
                    
                    <h5 class="border-bottom pb-2 mt-4">Message</h5>
                    <div class="message-content p-3 bg-light rounded">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire de suppression (hors modal) -->
<form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" id="deleteForm" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal de réponse -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Répondre au message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.contact-messages.reply', $message->id) }}" method="POST" id="replyForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="reply-to" class="form-label">Destinataire</label>
                        <input type="email" class="form-control" id="reply-to" name="email" value="{{ $message->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reply-subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="reply-subject" name="subject" value="Re: {{ $message->subject }}">
                    </div>
                    <div class="mb-3">
                        <label for="reply-content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="reply-content" name="content" rows="10" required>Bonjour {{ $message->name }},

Merci pour votre message.

Cordialement,
L'équipe d'administration</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="sendReplyBtn">Envoyer</button>
                </div>
            </form>
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page chargée, message ID: {{ $message->id }}');
    
    // Gestion du bouton Répondre
    const replyBtn = document.getElementById('replyBtn');
    if (replyBtn) {
        replyBtn.addEventListener('click', function(e) {
            console.log('Clic sur Répondre détecté');
            // Ouvrir le modal manuellement
            if (typeof $ !== 'undefined') {
                // Utiliser jQuery si disponible (AdminLTE utilise jQuery)
                $('#replyModal').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                // Utiliser Bootstrap natif
                var replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
                replyModal.show();
            } else {
                // Fallback
                document.getElementById('replyModal').style.display = 'block';
                document.getElementById('replyModal').classList.add('show');
            }
        });
    }
    
    // Gestion du bouton Supprimer
    const deleteBtn = document.getElementById('deleteBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            console.log('Clic sur Supprimer détecté');
            // Ouvrir le modal manuellement
            if (typeof $ !== 'undefined') {
                $('#deleteModal').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            } else {
                document.getElementById('deleteModal').style.display = 'block';
                document.getElementById('deleteModal').classList.add('show');
            }
        });
    }
    
    // Gestion du bouton de confirmation de suppression
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function(e) {
            console.log('Clic sur confirmation de suppression');
            document.getElementById('deleteForm').submit();
        });
    }
    
    // Écouter l'ouverture des modals pour debugging
    if (typeof $ !== 'undefined') {
        $('#replyModal').on('shown.bs.modal', function() {
            console.log('Modal de réponse affiché (jQuery)');
        });
        
        $('#deleteModal').on('shown.bs.modal', function() {
            console.log('Modal de suppression affiché (jQuery)');
        });
    }
    
    // Version alternative de debug pour les modals
    const checkModals = function() {
        console.log('Vérification des modals:');
        console.log('- replyModal visible:', document.getElementById('replyModal').classList.contains('show'));
        console.log('- deleteModal visible:', document.getElementById('deleteModal').classList.contains('show'));
    };
    
    // Vérifier après 1 seconde
    setTimeout(checkModals, 1000);
});
</script>
@endsection 