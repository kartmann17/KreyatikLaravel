<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\GlobalSettings;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            try {
                $currentUrl = $request->path();
                $currentPath = '/' . $currentUrl;

                Log::info('Current path: ' . $currentPath);

                // Récupérer les données SEO depuis la base de données
                $seo = SEO::where('url', $currentPath)->first();
                Log::info('SEO data found: ' . ($seo ? 'yes' : 'no'));

                if ($seo) {
                    $seoData = new SEOData(
                        title: $seo->title,
                        description: $seo->description,
                        author: $seo->author,
                        robots: $seo->robots,
                        canonical_url: $seo->canonical_url,
                        image: $seo->image,
                        locale: 'fr_FR',
                        site_name: 'Kréyatik Studio'
                    );
                } else {
                    $settings = GlobalSettings::getInstance();

                    $seoData = new SEOData(
                        title: 'Kréyatik Studio',
                        description: 'Création de sites internet modernes et performants',
                        author: 'Kréyatik Studio',
                        robots: 'index, follow',
                        canonical_url: url($currentPath),
                        image: $settings->default_image ? asset('storage/' . $settings->default_image) : null,
                        locale: 'fr_FR',
                        site_name: 'Kréyatik Studio'
                    );
                }

                view()->share('seoData', $seoData);
                return $next($request);
            } catch (\Exception $e) {
                Log::error('Error in SEO middleware: ' . $e->getMessage());
                return $next($request);
            }
        });
    }
}
