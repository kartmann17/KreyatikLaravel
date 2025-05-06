<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ClientController extends Controller
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
     * Affiche la liste des clients.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupérer les clients depuis la base de données
        $clients = \App\Models\Client::orderBy('name')->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }
    
    /**
     * Affiche le formulaire d'ajout de client.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.clients.create');
    }
    
    /**
     * Enregistre un nouveau client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        // Créer le client avec le modèle Client
        $client = new \App\Models\Client();
        $client->name = $request->name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->company = $request->company;
        $client->address = $request->address;
        $client->notes = $request->notes;
        $client->save();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client ajouté avec succès');
    }
    
    /**
     * Affiche la fiche d'un client.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        // Récupérer le client avec ses projets associés
        $client = \App\Models\Client::findOrFail($id);
        
        // Récupérer les projets liés à ce client
        $projects = $client->projects;
        
        return view('admin.clients.show', compact('client', 'projects'));
    }
    
    /**
     * Affiche le formulaire d'édition d'un client.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        // Récupérer le client
        $client = \App\Models\Client::findOrFail($id);
        
        return view('admin.clients.edit', compact('client'));
    }
    
    /**
     * Met à jour un client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        // Mettre à jour le client
        $client = \App\Models\Client::findOrFail($id);
        $client->update($request->all());
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }
    
    /**
     * Supprime un client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Supprimer le client
        $client = \App\Models\Client::findOrFail($id);
        $client->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès');
    }
} 