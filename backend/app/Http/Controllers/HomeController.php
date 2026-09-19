<?php

namespace App\Http\Controllers;

use App\Support\PageImages;
use App\Support\SeoResolver;
use App\Support\SiteSettings;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SeoResolver $seo, PageImages $images, SiteSettings $site): View
    {
        $page = config('site.pages.home');
        $document = $seo->forPage(
            'home',
            $page['fallback_title'],
            $page['fallback_description'],
            route('home'),
            [['name' => 'Главная', 'url' => route('home')]],
        );

        return view('pages.home', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
            'heroImage' => $images->url('home', 'hero', config('site.page_images.home.hero.fallback')),
            'heroAlt' => $images->alt('home', 'hero', config('site.page_images.home.hero.alt')),
        ]);
    }
}
