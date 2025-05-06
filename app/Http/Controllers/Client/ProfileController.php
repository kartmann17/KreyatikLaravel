<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Constructeur du contrôleur
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    /**
     * Affiche la page de profil de l'utilisateur client connecté
     */
    public function index()
    {
        return view('client.profile');
    }

    /**
     * Met à jour les informations de profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('client.profile.index')->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Met à jour le mot de passe de l'utilisateur
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('client.profile.index', ['#change-password'])->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

    /**
     * Met à jour les préférences de l'utilisateur
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        // Définir les préférences par défaut si elles n'existent pas
        if (!isset($user->preferences) || !is_array($user->preferences)) {
            $user->preferences = [];
        }
        
        // Fusionner les nouvelles préférences avec les existantes
        $preferences = $request->input('preferences', []);
        
        // Gérer les cases à cocher qui ne sont pas cochées
        $checkboxPreferences = ['notify_new_ticket', 'notify_ticket_update', 'notify_new_project'];
        foreach ($checkboxPreferences as $pref) {
            if (!isset($preferences[$pref])) {
                $preferences[$pref] = false;
            } else {
                $preferences[$pref] = true;
            }
        }
        
        $user->preferences = array_merge($user->preferences, $preferences);
        $user->save();

        return redirect()->route('client.profile.index', ['#preferences'])->with('success', 'Vos préférences ont été mises à jour avec succès.');
    }
} 