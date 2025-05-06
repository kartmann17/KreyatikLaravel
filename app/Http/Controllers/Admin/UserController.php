<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Affiche la liste des utilisateurs.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupérer les utilisateurs depuis la base de données avec filtrage
        $query = User::query();
        
        // Filtrer par rôle si spécifié
        if (request('role') && in_array(request('role'), ['admin', 'staff', 'client'])) {
            $query->where('role', request('role'));
        }
        
        // Recherche par nom ou email
        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }
        
        // Tri par nom par défaut
        $users = $query->orderBy('name')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Affiche le formulaire de modification d'un utilisateur.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $clients = Client::orderBy('name')->get();
        
        return view('admin.users.edit', compact('user', 'clients'));
    }
    
    /**
     * Met à jour les informations d'un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,staff,client',
            'client_id' => 'nullable|exists:clients,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        // Si le rôle est client et qu'un client est sélectionné
        if ($request->role === 'client' && $request->client_id) {
            $user->client_id = $request->client_id;
        } elseif ($request->role !== 'client') {
            $user->client_id = null;
        }
        
        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }
    
    /**
     * Supprime un utilisateur.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }
        
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
    
    /**
     * Change le rôle d'un utilisateur en client et l'associe à un client existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeToClient(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);
        
        $user->role = 'client';
        $user->client_id = $request->client_id;
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur converti en client avec succès');
    }

    /**
     * Affiche le formulaire de création d'un utilisateur.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('admin.users.create', compact('clients'));
    }
    
    /**
     * Enregistre un nouvel utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,staff,client',
            'client_id' => 'nullable|exists:clients,id',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        
        // Si le rôle est client et qu'un client est sélectionné
        if ($request->role === 'client' && $request->client_id) {
            $user->client_id = $request->client_id;
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }
} 