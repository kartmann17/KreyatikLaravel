<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'technology',
        'type', // 'image' ou 'video'
        'path',
        'order', // Pour permettre de réordonner les éléments
        'is_visible', // Pour activer/désactiver l'affichage d'un élément
    ];

    /**
     * Les types d'éléments du portfolio.
     */
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';

    /**
     * Obtenez le chemin complet de l'élément (image ou vidéo).
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        return asset($this->path);
    }

    /**
     * Vérifie si l'élément est une image.
     *
     * @return bool
     */
    public function isImage()
    {
        return $this->type === self::TYPE_IMAGE;
    }

    /**
     * Vérifie si l'élément est une vidéo.
     *
     * @return bool
     */
    public function isVideo()
    {
        return $this->type === self::TYPE_VIDEO;
    }

    /**
     * Scope pour récupérer uniquement les éléments visibles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope pour ordonner les éléments selon leur ordre défini.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
} 