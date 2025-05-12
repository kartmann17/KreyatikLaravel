<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GlobalSettings;
use RalphJSmit\Laravel\SEO\Models\SEO;

class CheckSeoSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les paramètres SEO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification des paramètres SEO...');

        // Vérifier les paramètres globaux
        $settings = GlobalSettings::getInstance();
        $this->info("\nParamètres globaux :");
        $this->table(
            ['Clé', 'Valeur'],
            collect($settings->toArray())->map(function ($value, $key) {
                return [$key, $value];
            })
        );

        // Vérifier les métadonnées SEO
        $seoData = $settings->seo()->first();
        if ($seoData) {
            $this->info("\nMétadonnées SEO globales :");
            $this->table(
                ['Clé', 'Valeur'],
                collect($seoData->toArray())->map(function ($value, $key) {
                    return [$key, $value];
                })
            );
        }

        // Vérifier les métadonnées SEO par page
        $pages = ['home', 'contact', 'offres', 'client', 'portfolio'];
        foreach ($pages as $page) {
            $url = match ($page) {
                'home' => '/',
                'offres' => '/NosOffres',
                'contact' => '/Contact',
                'client' => '/Client',
                'portfolio' => '/Portfolio',
                default => '/'
            };

            $pageSeo = SEO::where('url', $url)->first();
            if ($pageSeo) {
                $this->info("\nMétadonnées SEO pour la page {$page} :");
                $this->table(
                    ['Clé', 'Valeur'],
                    collect($pageSeo->toArray())->map(function ($value, $key) {
                        return [$key, $value];
                    })
                );
            }
        }
    }
}
