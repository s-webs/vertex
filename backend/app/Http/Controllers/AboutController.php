<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\PageImages;
use App\Support\SeoResolver;
use App\Support\SiteSettings;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(SeoResolver $seo, PageImages $images, SiteSettings $site): View
    {
        $page = config('site.pages.about');
        $document = $seo->forPage(
            'about',
            $page['fallback_title'],
            $page['fallback_description'],
            route('about'),
            [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'О компании', 'url' => route('about')],
            ],
        );

        return view('pages.about', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
            'documents' => Document::query()->published()->ordered()->get(),
            'sideImage' => $images->url('about', 'side', $site->logoUrl()),
            'sideAlt' => $images->alt('about', 'side', config('site.page_images.about.side.alt')),
        ]);
    }
}
