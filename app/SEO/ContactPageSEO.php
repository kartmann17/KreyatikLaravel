<?php

namespace App\SEO;

use RalphJSmit\Laravel\SEO\Support\SEOData;

class ContactPageSEO
{
    public function getData(): SEOData
    {
        return new SEOData(
            title: 'Contactez-nous | Votre Entreprise',
            description: 'Prenez contact avec notre équipe pour discuter de vos projets ou obtenir plus d\'informations sur nos services.',
            url: url('contact'),
            image: asset('images/contact-banner.png'),
            locale: 'fr_FR',
            site_name: config('app.name'),
            published_time: now(),
            modified_time: now(),
        );
    }
} 