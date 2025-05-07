<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'client_id',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'json',
        ];
    }

    /**
     * Récupère le client associé à l'utilisateur (pour les comptes clients)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un membre du staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Vérifie si l'utilisateur est un client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Retourne l'URL de l'image de profil pour AdminLTE
     */
    public function adminlte_image()
    {
        // Utiliser une URL d'avatar générique de UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Retourne la description pour AdminLTE
     */
    public function adminlte_desc()
    {
        return ucfirst($this->role);
    }

    /**
     * Retourne l'URL de profil pour AdminLTE
     */
    public function adminlte_profile_url()
    {
        // URL vers la page de profil selon le rôle
        if ($this->isAdmin()) {
            return route('admin.profile.index');
        } elseif ($this->isClient()) {
            return route('client.profile.index');
        }

        return route('admin.profile.index');
    }
}
