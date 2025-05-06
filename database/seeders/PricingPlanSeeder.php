<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricingPlan;
use Illuminate\Support\Str;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les plans existants pour éviter les doublons
        PricingPlan::truncate();
        
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'monthly_price' => '49',
                'annual_price' => '490',
                'features' => [
                    'Site vitrine personnalisé ou e-commerce (Shopify, Wix)',
                    'Nom de domaine & hébergement inclus la première année',
                    'Maintenance & support'
                ],
                'button_text' => "S'abonner",
                'starting_text' => 'À partir de',
                'is_active' => true,
                'is_highlighted' => false,
                'is_custom_plan' => false,
                'order' => 1,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'monthly_price' => '99',
                'annual_price' => '990',
                'features' => [
                    'Site pro ou e-commerce complet',
                    'Optimisation SEO avancée',
                    'Nom de domaine, hébergement inclus la première année, support & mises à jour'
                ],
                'button_text' => "S'abonner",
                'starting_text' => 'À partir de',
                'is_active' => true,
                'is_highlighted' => true,
                'highlight_text' => 'Le plus populaire',
                'is_custom_plan' => false,
                'order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'monthly_price' => '199',
                'annual_price' => '1990',
                'features' => [
                    'Site haut de gamme sur mesure',
                    'Consulting & stratégie digitale',
                    'Assistance prioritaire & SEO expert',
                    'Nom de domaine, hébergement inclus la première année support & mises à jour'
                ],
                'button_text' => "S'abonner",
                'starting_text' => 'À partir de',
                'is_active' => true,
                'is_highlighted' => false,
                'is_custom_plan' => false,
                'order' => 3,
            ],
            [
                'name' => 'Site sur mesure',
                'slug' => 'sur-mesure',
                'monthly_price' => 'Sur devis',
                'annual_price' => 'Sur devis',
                'features' => [
                    'Design unique selon vos besoins',
                    'Développement sur-mesure',
                    'Accompagnement personnalisé & suivi',
                    'Fonctionnalités spécifiques & intégrations avancées'
                ],
                'button_text' => "S'abonner",
                'starting_text' => 'Projet 100% personnalisé',
                'is_active' => true,
                'is_highlighted' => false,
                'is_custom_plan' => true,
                'custom_plan_text' => 'Contactez-nous pour une estimation',
                'order' => 4,
            ]
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }
    }
}
