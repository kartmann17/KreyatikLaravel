<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class NosOffresController extends Controller
{
    public function index()
    {
        $SEOData = new SEOData(
            title: 'Nos Offres | Votre Entreprise',
            description: 'Découvrez nos offres et tarifs adaptés à vos besoins. Solutions sur mesure pour tous vos projets.',
            url: url()->current(),
            image: asset('images/logo.png'),
            locale: 'fr_FR',
            site_name: config('app.name'),
        );

        // Récupération des plans tarifaires actifs, triés par ordre
        $pricingPlans = PricingPlan::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('nosoffres.index', compact('SEOData', 'pricingPlans'));
    }
} 