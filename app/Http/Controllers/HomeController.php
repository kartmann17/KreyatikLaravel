<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $seo = new SEOData(
            title: 'Accueil',
            description: 'Bienvenue sur Kréyatik Studio - Création de sites internet modernes et performants',
            author: 'Kréyatik Studio',
            robots: 'index, follow',
            image: asset('images/logo.png'),
        );

        return view('welcome', [
            'seo' => $seo,
        ]);
    }
}
