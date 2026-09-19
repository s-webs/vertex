<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Work;
use App\Support\Media;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'images' => []],
            ['loc' => route('catalog'), 'images' => []],
            ['loc' => route('about'), 'images' => []],
            ['loc' => route('works.index'), 'images' => []],
            ['loc' => route('contacts.show'), 'images' => []],
        ];

        foreach (Catalog::query()->published()->ordered()->get() as $catalog) {
            if ($catalog->cover_path) {
                $urls[1]['images'][] = [
                    'loc' => Media::url($catalog->cover_path),
                    'title' => $catalog->title,
                ];
            }
        }

        foreach (Work::query()->published()->ordered()->with('photos')->get() as $work) {
            $urls[] = [
                'loc' => route('works.show', $work),
                'images' => $work->photos->map(fn ($photo): array => [
                    'loc' => Media::url($photo->path),
                    'title' => $photo->caption ?: $work->title,
                ])->filter(fn (array $image): bool => filled($image['loc']))->all(),
            ];
        }

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
