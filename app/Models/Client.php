<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'address', 'notes'
    ];

    /**
     * Retourne les projets associés à ce client
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
