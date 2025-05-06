@extends('admin.layout')

@section('title', 'Gestion du Portfolio')

@section('page_title', 'Gestion du Portfolio')

@section('content_body')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Éléments du Portfolio</h3>
                <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un élément
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(count($portfolioItems) > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="portfolio-table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 100px">Aperçu</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Technologie</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-items">
                            @foreach($portfolioItems as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->order }}</td>
                                    <td>
                                        @if($item->isImage())
                                            <img src="{{ asset($item->path) }}" alt="{{ $item->title }}" class="img-thumbnail" style="max-height: 60px;">
                                        @else
                                            <video muted class="img-thumbnail" style="max-height: 60px;">
                                                <source src="{{ asset($item->path) }}" type="video/mp4">
                                                Votre navigateur ne supporte pas la vidéo.
                                            </video>
                                        @endif
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->description, 50) }}</td>
                                    <td>{{ $item->technology }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->isImage() ? 'info' : 'warning' }}">
                                            {{ $item->isImage() ? 'Image' : 'Vidéo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.portfolio.visibility', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $item->is_visible ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $item->is_visible ? 'Visible' : 'Masqué' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.portfolio.edit', $item->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default handle">
                                                <i class="fas fa-arrows-alt"></i>
                                            </button>
                                        </div>

                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="delete-modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer cet élément du portfolio: <strong>{{ $item->title }}</strong> ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('admin.portfolio.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Aucun élément dans le portfolio. <a href="{{ route('admin.portfolio.create') }}">Ajoutez-en un</a> pour commencer.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        // Initialiser les vidéos pour qu'elles soient muettes
        $('video').each(function() {
            this.muted = true;
        });

        // Rendre la liste triable
        $('#sortable-items').sortable({
            handle: '.handle',
            update: function(event, ui) {
                let items = [];
                $('#sortable-items tr').each(function() {
                    items.push($(this).data('id'));
                });
                
                // Envoyer l'ordre mis à jour au serveur
                $.ajax({
                    url: '{{ route("admin.portfolio.order") }}',
                    method: 'POST',
                    data: {
                        items: items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Mettre à jour les numéros d'ordre dans l'interface
                            $('#sortable-items tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                            
                            // Notification de succès
                            toastr.success('Ordre mis à jour avec succès.');
                        }
                    },
                    error: function() {
                        toastr.error('Une erreur est survenue lors de la mise à jour de l\'ordre.');
                    }
                });
            }
        });
    });
</script>
@endsection 