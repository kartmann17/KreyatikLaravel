@extends('admin.layout')

@section('title', 'Ajouter un élément au Portfolio')

@section('page_title', 'Ajouter un élément au Portfolio')

@section('content_body')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Nouvel élément</h3>
                <a href="{{ route('admin.portfolio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="technology">Technologies utilisées</label>
                            <input type="text" class="form-control" id="technology" name="technology" value="{{ old('technology') }}" placeholder="ex: HTML, CSS, JavaScript, Laravel, etc.">
                            <small class="form-text text-muted">Les technologies utilisées pour réaliser ce projet (séparées par des virgules).</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Type de média <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="file">Fichier (Image/Vidéo) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" required accept=".jpg,.jpeg,.png,.gif,.mp4,.webm">
                                    <label class="custom-file-label" for="file">Choisir un fichier</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Formats acceptés: JPEG, PNG, GIF, MP4, WEBM. Taille max: 20MB.</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_visible" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_visible">Visible sur le site</label>
                            </div>
                            <small class="form-text text-muted">Décochez cette case pour masquer cet élément du portfolio sur le site public.</small>
                        </div>

                        <div class="form-group mt-4">
                            <div id="preview-container" class="text-center d-none">
                                <h5>Aperçu</h5>
                                <div id="image-preview" class="d-none">
                                    <img src="" alt="Aperçu" class="img-fluid img-thumbnail" style="max-height: 200px;">
                                </div>
                                <div id="video-preview" class="d-none">
                                    <video controls class="img-fluid img-thumbnail" style="max-height: 200px;">
                                        Votre navigateur ne supporte pas la vidéo.
                                    </video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
    $(function() {
        // Afficher le nom du fichier sélectionné
        $('#file').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choisir un fichier');
            
            // Prévisualisation du fichier
            previewFile(this);
        });
        
        // Fonction pour prévisualiser le fichier
        function previewFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var file = input.files[0];
                var type = $('#type').val();
                
                $('#preview-container').removeClass('d-none');
                
                if (type == 'image') {
                    $('#image-preview').removeClass('d-none');
                    $('#video-preview').addClass('d-none');
                    
                    reader.onload = function(e) {
                        $('#image-preview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else if (type == 'video') {
                    $('#video-preview').removeClass('d-none');
                    $('#image-preview').addClass('d-none');
                    
                    // Pour les vidéos, nous devons recréer l'élément vidéo pour éviter les problèmes de chargement
                    var videoPreview = $('#video-preview');
                    videoPreview.html('<video controls class="img-fluid img-thumbnail" style="max-height: 200px;"></video>');
                    
                    reader.onload = function(e) {
                        var video = videoPreview.find('video')[0];
                        video.src = e.target.result;
                        video.load();
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
        
        // Réinitialiser l'aperçu lors du changement de type
        $('#type').on('change', function() {
            $('#file').val('');
            $('.custom-file-label').html('Choisir un fichier');
            $('#preview-container').addClass('d-none');
        });
    });
</script>
@endsection 