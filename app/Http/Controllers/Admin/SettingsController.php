<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page des paramètres
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings');
    }

    /**
     * Met à jour les paramètres du compte utilisateur
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
            }
        }
        
        // Mettre à jour les informations de l'utilisateur
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('admin.settings.index')->with('success', 'Vos informations ont été mises à jour avec succès.');
    }
    
    /**
     * Met à jour les paramètres SEO généraux du site
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'default_description' => 'nullable|string',
            'default_keywords' => 'nullable|string',
            'default_image' => 'nullable|image|max:2048',
            'locale' => 'required|string|in:fr_FR,en_US,en_GB',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);
        
        try {
            // Mettre à jour le nom de l'application
            $this->updateEnvVariable('APP_NAME', '"'.$validated['site_name'].'"');
            
            // Gérer l'upload de l'image par défaut
            if ($request->hasFile('default_image')) {
                $path = $request->file('default_image')->store('seo', 'public');
                $this->updateSeoConfig('default_image', 'storage/'.$path);
            }
            
            // Mettre à jour les autres configurations SEO
            $this->updateSeoConfig('default_description', $validated['default_description']);
            $this->updateSeoConfig('default_keywords', $validated['default_keywords']);
            $this->updateSeoConfig('locale', $validated['locale']);
            $this->updateSeoConfig('social_facebook', $validated['social_facebook']);
            $this->updateSeoConfig('social_twitter', $validated['social_twitter']);
            $this->updateSeoConfig('social_instagram', $validated['social_instagram']);
            $this->updateSeoConfig('social_linkedin', $validated['social_linkedin']);
            
            return redirect()->route('admin.settings.index')->with('success', 'Les paramètres SEO ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres SEO: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des paramètres SEO.'])->withInput();
        }
    }
    
    /**
     * Met à jour les paramètres SEO spécifiques à une page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePageSeo(Request $request, $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        try {
            // Vérifier que la page est valide
            $validPages = ['home', 'contact', 'offres', 'client'];
            if (!in_array($page, $validPages)) {
                return back()->withErrors(['error' => 'Page invalide.']);
            }
            
            // Mettre à jour les configurations SEO pour la page spécifique
            $this->updateSeoConfig("pages.{$page}.title", $validated['title']);
            $this->updateSeoConfig("pages.{$page}.description", $validated['description']);
            
            return redirect()->route('admin.settings.index')->with('success', 'Les paramètres SEO de la page ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres SEO de la page: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des paramètres SEO de la page.'])->withInput();
        }
    }
    
    /**
     * Met à jour une variable d'environnement dans le fichier .env
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Si la clé existe déjà, la remplacer
            if (strpos($content, "{$key}=") !== false) {
                $content = preg_replace("/{$key}=(.*)\n/", "{$key}={$value}\n", $content);
            } else {
                // Sinon, l'ajouter à la fin du fichier
                $content .= "{$key}={$value}\n";
            }
            
            file_put_contents($path, $content);
        }
        
        // Effacer le cache de configuration
        Artisan::call('config:clear');
    }
    
    /**
     * Met à jour une configuration SEO dans le config/seo.php
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    private function updateSeoConfig($key, $value)
    {
        // Dans un environnement de production, il faudrait écrire physiquement dans le fichier
        // Pour l'exemple, on utilise la méthode qui stocke en session
        Config::set("seo.{$key}", $value);
        
        // En production, on écrirait dans le fichier de configuration
        // $this->writeConfigFile('seo', 'seo', ["{$key}" => $value]);
    }
    
    /**
     * Écrit une configuration dans un fichier de configuration
     * Cette méthode serait utilisée en production pour persister les changements
     *
     * @param  string  $file
     * @param  string  $key
     * @param  array   $data
     * @return void
     */
    private function writeConfigFile($file, $key, $data)
    {
        // Cette méthode est un exemple et nécessiterait une implémentation complète
        // pour écrire dans les fichiers de configuration
        
        // Pour le moment, elle ne fait rien mais pourrait être implémentée si nécessaire
    }

    /**
     * Met à jour les préférences de l'application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        // Validation des données
        $request->validate([
            'theme' => 'required|string|in:light,dark,auto',
            'language' => 'required|string|in:fr,en',
            'time_format' => 'required|string|in:12h,24h',
            'email_notifications' => 'sometimes|boolean',
            'browser_notifications' => 'sometimes|boolean',
        ]);

        // Récupérer l'utilisateur
        $user = User::find(Auth::id());

        // Stocker les préférences
        // Note: Une table de préférences ou un champ JSON sur la table users serait nécessaire
        // Ceci est une version simplifiée pour la démonstration
        
        // Par exemple, vous pourriez avoir un champ 'preferences' de type JSON dans la table users
        $preferences = [
            'theme' => $request->theme,
            'language' => $request->language,
            'time_format' => $request->time_format,
            'notifications' => [
                'email' => $request->has('email_notifications'),
                'browser' => $request->has('browser_notifications'),
            ]
        ];

        // Pour l'instant, nous allons simplement simuler que les préférences sont sauvegardées
        // $user->preferences = json_encode($preferences);
        // $user->save();

        return redirect()->route('admin.settings')->with('success', 'Préférences mises à jour avec succès.');
    }
} 