<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Support\SeoResolver;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __invoke(SeoResolver $seo): View
    {
        $page = config('site.pages.catalog');
        $document = $seo->forPage(
            'catalog',
            $page['fallback_title'],
            $page['fallback_description'],
            route('catalog'),
            [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Каталоги', 'url' => route('catalog')],
            ],
        );

        return view('pages.catalog', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
            'catalogs' => Catalog::query()->published()->ordered()->get(),
        ]);
    }
}
