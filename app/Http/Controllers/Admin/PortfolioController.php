<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Affiche la liste des éléments du portfolio.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $portfolioItems = PortfolioItem::orderBy('order', 'asc')->get();
        
        return view('admin.portfolio.index', compact('portfolioItems'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel élément.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.portfolio.create');
    }

    /**
     * Enregistre un nouvel élément dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Vérifier les permissions des dossiers de stockage
        $this->checkStorageFolders();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technology' => 'nullable|string|max:255',
            'type' => 'required|in:image,video',
            'file' => 'required|file|max:20480',
        ]);

        // Vérification supplémentaire du format de fichier en fonction du type sélectionné
        $file = $request->file('file');
        $fileType = $validated['type'];
        
        if (!$this->validateFileType($file, $fileType)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Le format du fichier ne correspond pas au type sélectionné.']);
        }

        // Gestion du fichier (image ou vidéo)
        $path = $this->handleFileUpload($file, $validated['type']);
        
        // Vérifier si le téléchargement a réussi
        if (empty($path)) {
            // Journaliser l'erreur pour le débogage
            \Log::error('Échec du téléchargement du fichier portfolio', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Le téléchargement du fichier a échoué. Veuillez réessayer avec un fichier différent.']);
        }

        // Déterminer l'ordre (dernier + 1)
        $lastOrder = PortfolioItem::max('order') ?? 0;

        // Création de l'élément
        PortfolioItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'technology' => $validated['technology'],
            'type' => $validated['type'],
            'path' => $path,
            'order' => $lastOrder + 1,
            'is_visible' => $request->has('is_visible') ? true : false,
        ]);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Élément du portfolio ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un élément.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        
        return view('admin.portfolio.edit', compact('portfolioItem'));
    }

    /**
     * Met à jour un élément dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Vérifier les permissions des dossiers de stockage
        $this->checkStorageFolders();
        
        $portfolioItem = PortfolioItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technology' => 'nullable|string|max:255',
            'type' => 'required|in:image,video',
            'file' => 'nullable|file|max:20480',
        ]);

        // Gestion du fichier (image ou vidéo) si un nouveau fichier est fourni
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $validated['type'];
            
            if (!$this->validateFileType($file, $fileType)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file' => 'Le format du fichier ne correspond pas au type sélectionné.']);
            }
            
            // Supprimer l'ancien fichier
            $this->deleteFile($portfolioItem->path);
            
            // Télécharger le nouveau fichier
            $path = $this->handleFileUpload($file, $validated['type']);
            
            // Vérifier si le téléchargement a réussi
            if (empty($path)) {
                // Journaliser l'erreur pour le débogage
                \Log::error('Échec du téléchargement du fichier portfolio lors de la mise à jour', [
                    'id' => $id,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file' => 'Le téléchargement du fichier a échoué. Veuillez réessayer avec un fichier différent.']);
            }
            
            $portfolioItem->path = $path;
        }

        // Mise à jour des autres champs
        $portfolioItem->title = $validated['title'];
        $portfolioItem->description = $validated['description'];
        $portfolioItem->technology = $validated['technology'];
        $portfolioItem->type = $validated['type'];
        $portfolioItem->is_visible = $request->has('is_visible') ? true : false;
        
        $portfolioItem->save();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Élément du portfolio mis à jour avec succès.');
    }

    /**
     * Supprime un élément du portfolio.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);

        // Suppression du fichier
        $this->deleteFile($portfolioItem->path);

        // Suppression de l'enregistrement
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Élément du portfolio supprimé avec succès.');
    }

    /**
     * Met à jour l'ordre des éléments du portfolio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:portfolio_items,id',
        ]);

        foreach ($validated['items'] as $index => $id) {
            PortfolioItem::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bascule la visibilité d'un élément.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleVisibility($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        $portfolioItem->is_visible = !$portfolioItem->is_visible;
        $portfolioItem->save();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Visibilité mise à jour avec succès.');
    }

    /**
     * Gère le téléchargement des fichiers (images ou vidéos).
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $type
     * @return string
     */
    private function handleFileUpload($file, $type)
    {
        // Vérifications plus strictes du fichier
        if (!$file) {
            \Log::error('Aucun fichier fourni pour le téléchargement');
            return '';
        }
        
        if (!$file->isValid()) {
            \Log::error('Fichier invalide: ' . $file->getErrorMessage());
            return '';
        }
        
        try {
            // Préparation des chemins et noms de fichiers
            $folder = $type === 'image' ? 'images/portfolio' : 'videos/portfolio';
            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Méthode entièrement manuelle - ne pas utiliser le système de fichiers de Laravel
            
            // Créer le dossier s'il n'existe pas
            $publicPath = public_path('storage/' . $folder);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Déplacer le fichier directement
            $uploadSuccess = move_uploaded_file(
                $file->getPathname(),
                $publicPath . '/' . $fileName
            );
            
            if (!$uploadSuccess) {
                \Log::error('Échec du déplacement du fichier téléchargé', [
                    'source' => $file->getPathname(),
                    'destination' => $publicPath . '/' . $fileName
                ]);
                return '';
            }
            
            return "storage/{$folder}/{$fileName}";
        } catch (\Exception $e) {
            \Log::error('Exception lors du téléchargement du fichier: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }

    /**
     * Supprime un fichier du stockage.
     *
     * @param  string  $path
     * @return bool
     */
    private function deleteFile($path)
    {
        // Vérifier si le chemin est vide
        if (empty($path)) {
            return false;
        }
        
        // Convertir le chemin public en chemin de stockage
        $storagePath = str_replace('storage/', 'public/', $path);
        
        return Storage::exists($storagePath) ? Storage::delete($storagePath) : false;
    }

    /**
     * Valide que le type de fichier correspond au type sélectionné (image/vidéo)
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type
     * @return bool
     */
    private function validateFileType($file, $type)
    {
        if (!$file || !$file->isValid()) {
            return false;
        }
        
        // Récupérer le type MIME et l'extension
        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Liste d'extensions acceptables
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $videoExtensions = ['mp4', 'webm', 'avi', 'mov', 'mpeg', 'mpg', '3gp'];
        
        if ($type === 'image') {
            // Validation permissive - accepter sur la base du MIME OU de l'extension
            return str_starts_with($mime, 'image/') || in_array($extension, $imageExtensions);
        } elseif ($type === 'video') {
            // Validation permissive - accepter sur la base du MIME OU de l'extension
            return str_starts_with($mime, 'video/') || in_array($extension, $videoExtensions);
        }
        
        return false;
    }

    /**
     * Vérifie et prépare les dossiers de stockage pour le portfolio
     */
    private function checkStorageFolders()
    {
        $paths = [
            public_path('storage'),
            public_path('storage/images'),
            public_path('storage/videos'),
            public_path('storage/images/portfolio'),
            public_path('storage/videos/portfolio')
        ];
        
        foreach ($paths as $path) {
            if (!file_exists($path)) {
                \Log::info("Création du dossier manquant: {$path}");
                mkdir($path, 0755, true);
            }
            
            // Vérifier les permissions
            if (!is_writable($path)) {
                chmod($path, 0755);
                \Log::warning("Mise à jour des permissions pour: {$path}");
            }
        }
    }
}
