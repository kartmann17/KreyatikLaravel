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
        // Récupération des plans tarifaires actifs, triés par ordre
        $pricingPlans = PricingPlan::where('is_active', true)
            ->orderBy('order')
            ->get();

        $seo = new SEOData(
            title: 'Nos Offres',
            description: 'Découvrez nos offres et tarifs adaptés à vos besoins. Solutions sur mesure pour tous vos projets.',
            author: 'Kréyatik Studio',
            robots: 'index, follow',
            image: asset('images/logo.png'),
        );

        return view('nosoffres.index', [
            'pricingPlans' => $pricingPlans,
            'seo' => $seo,
        ]);
    }
}
