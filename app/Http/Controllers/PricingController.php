<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PricingController extends Controller
{
    /**
     * Affiche la page des tarifs avec tous les plans disponibles
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupération des plans tarifaires actifs, triés par ordre
        $pricingPlans = Cache::remember('pricing-plans-public', 60 * 30, function () {
            return PricingPlan::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
        
        // Récupérer les paramètres pour la section de plan personnalisé
        $customPlanText = setting('pricing_custom_plan_text', 'Vous avez des besoins spécifiques ? Contactez-nous pour une solution sur mesure adaptée à votre activité.');
        $customPlanTitle = setting('pricing_custom_plan_title', 'Besoin d\'une solution personnalisée ?');
        $customPlanButtonText = setting('pricing_custom_plan_button', 'Demander un devis');
        
        return view('pricing', compact(
            'pricingPlans',
            'customPlanText',
            'customPlanTitle',
            'customPlanButtonText'
        ));
    }
    
    /**
     * Affiche les détails d'un plan spécifique
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $plan = PricingPlan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        return view('pricing-plan', compact('plan'));
    }
} 