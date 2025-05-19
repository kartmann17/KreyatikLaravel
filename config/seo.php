<?php

return [
  'model' => 'RalphJSmit\\Laravel\\SEO\\Models\\SEO',
  'site_name' => env('APP_NAME', 'Kréyatik Studio'),
  'sitemap' => '/sitemap.xml',
  'canonical_link' => true,
  'robots' => [
    'default' => 'max-snippet:-1,max-image-preview:large,max-video-preview:-1',
    'force_default' => false,
  ],
  'favicon' => 'favicon.ico',
  'title' => [
    'infer_title_from_url' => true,
    'suffix' => ' | Kréyatik Studio',
    'homepage_title' => 'Kréyatik Studio - Création de sites internet modernes et performants',
  ],
  'description' => [
    'fallback' => 'Création de sites internet modernes et performants. Solutions sur mesure pour tous vos projets digitaux.',
  ],
  'image' => [
    'fallback' => null,
  ],
  'author' => [
    'fallback' => 'Kréyatik Studio',
  ],
  'twitter' => [
    '@username' => null,
  ],
  'default_description' => null,
  'default_keywords' => null,
  'default_image' => null,
  'locale' => 'fr_FR',
  'social_facebook' => null,
  'social_twitter' => null,
  'social_instagram' => null,
  'social_linkedin' => null,
  'pages' => [
    'home' => [
      'title' => 'Accueil | Kréyatik Studio',
      'description' => 'Bienvenue sur Kréyatik Studio - Création de sites internet modernes et performants',
    ],
    'contact' => [
      'title' => 'Contactez-nous | Kréyatik Studio',
      'description' => 'Prenez contact avec notre équipe pour discuter de vos projets ou obtenir plus d\'informations sur nos services.',
    ],
    'offres' => [
      'title' => 'Nos Offres | Kréyatik Studio',
      'description' => 'Découvrez nos offres et tarifs adaptés à vos besoins. Solutions sur mesure pour tous vos projets.',
    ],
    'portfolio' => [
      'title' => 'Notre Portfolio  | Kréyatik Studio',
      'description' => 'Découvrez notre portfolio créatif, présentant une sélection de projets uniques en design web, développement et branding, réalisés pour des clients dans divers secteurs d\'activité.',
    ],
    'client' => [
      'title' => 'Espace Client | Kréyatik Studio',
      'description' => 'Accédez à votre espace client pour gérer vos projets et suivre vos demandes en cours.',
    ],
  ],
];
