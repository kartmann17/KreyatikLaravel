<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $fillable = [
        'site_name',
        'default_description',
        'default_keywords',
        'default_image',
        'locale',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
    ];

    public static function getInstance()
    {
        return static::firstOrCreate(['id' => 1], [
            'site_name' => 'Kréyatik Studio',
            'default_description' => 'Création de sites internet modernes et performants',
            'default_keywords' => 'création site web, développement web, design web',
            'default_image' => null,
            'locale' => 'fr_FR',
            'social_facebook' => null,
            'social_twitter' => null,
            'social_instagram' => 'https://www.instagram.com/kreyatik_17',
            'social_linkedin' => null,
        ]);
    }
}
