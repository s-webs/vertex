<?php

namespace App\Http\Controllers;

use App\Support\SiteSettings;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(SiteSettings $settings): Response
    {
        $extra = trim((string) $settings->get('robots_extra', ''));
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Allow: /',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        if ($extra !== '') {
            $lines[] = $extra;
        }

        return response(implode("\n", $lines)."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
