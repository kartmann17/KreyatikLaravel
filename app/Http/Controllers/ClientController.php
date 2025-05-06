<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ClientController extends Controller
{
    public function index()
    {
        $SEOData = new SEOData(
            title: 'Espace Client | Votre Entreprise',
            description: 'Accédez à votre espace client pour gérer vos projets et suivre vos demandes en cours.',
            url: url()->current(),
            image: asset('images/logo.png'),
            locale: 'fr_FR',
            site_name: config('app.name'),
        );

        return view('client.index', compact('SEOData'));
    }
} 