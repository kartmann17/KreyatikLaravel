<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GlobalSettings;
use RalphJSmit\Laravel\SEO\Models\SEO;
use Illuminate\Support\Facades\Log;

class InitSeoData extends Command
{
    protected $signature = 'seo:init';
    protected $description = 'Initialise les données SEO pour toutes les pages';

    public function handle()
    {
        try {
            $settings = GlobalSettings::getInstance();

            if (!$settings) {
                $this->error('Aucune instance de GlobalSettings trouvée.');
                $settings = GlobalSettings::create([
                    'site_name' => 'Kréyatik Studio',
                    'locale' => 'fr_FR'
                ]);
                $this->info('Instance GlobalSettings créée avec succès.');
            }

            $this->info('ID de GlobalSettings : ' . $settings->id);

            $pages = [
                '/' => [
                    'title' => 'Accueil | Kréyatik Studio',
                    'description' => 'Bienvenue sur Kréyatik Studio - Création de sites internet modernes et performants',
                ],
                '/Contact' => [
                    'title' => 'Contactez-nous | Kréyatik Studio',
                    'description' => 'Prenez contact avec notre équipe pour discuter de vos projets ou obtenir plus d\'informations sur nos services.',
                ],
                '/NosOffres' => [
                    'title' => 'Nos Offres | Kréyatik Studio',
                    'description' => 'Découvrez nos offres et tarifs adaptés à vos besoins. Solutions sur mesure pour tous vos projets.',
                ],
                '/Portfolio' => [
                    'title' => 'Notre Portfolio | Kréyatik Studio',
                    'description' => 'Découvrez notre portfolio créatif, présentant une sélection de projets uniques en design web, développement et branding.',
                ],
                '/Client' => [
                    'title' => 'Espace Client | Kréyatik Studio',
                    'description' => 'Accédez à votre espace client pour gérer vos projets et suivre vos demandes en cours.',
                ],
            ];

            foreach ($pages as $url => $data) {
                $this->info('Traitement de la page : ' . $url);

                try {
                    $seo = SEO::updateOrCreate(
                        [
                            'url' => $url,
                            'model_type' => GlobalSettings::class,
                            'model_id' => $settings->id,
                        ],
                        [
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'author' => $settings->site_name,
                            'robots' => 'index, follow',
                            'canonical_url' => url($url),
                        ]
                    );

                    $this->info('SEO créé/mis à jour pour ' . $url . ' avec l\'ID : ' . $seo->id);
                } catch (\Exception $e) {
                    $this->error('Erreur lors de la création/mise à jour du SEO pour ' . $url);
                    $this->error($e->getMessage());
                    Log::error('Erreur SEO pour ' . $url . ': ' . $e->getMessage());
                }
            }

            // Vérification finale
            $totalSeo = SEO::count();
            $this->info('Nombre total d\'entrées SEO : ' . $totalSeo);

            $this->info('Données SEO initialisées avec succès !');
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue : ' . $e->getMessage());
            Log::error('Erreur lors de l\'initialisation SEO : ' . $e->getMessage());
        }
    }
}
