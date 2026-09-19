<?php

namespace App\Http\Controllers;

use App\Enums\SeoOwnerType;
use App\Models\Work;
use App\Support\SeoResolver;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function index(SeoResolver $seo): View
    {
        $page = config('site.pages.works');
        $document = $seo->forPage(
            'works',
            $page['fallback_title'],
            $page['fallback_description'],
            route('works.index'),
            [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Наши работы', 'url' => route('works.index')],
            ],
        );

        return view('pages.works.index', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
            'works' => Work::query()->published()->ordered()->with('photos')->get(),
        ]);
    }

    public function show(Work $work, SeoResolver $seo): View
    {
        abort_unless($work->is_published, 404);

        $work->load('photos');
        $document = $seo->forOwner(
            SeoOwnerType::Work,
            (string) $work->id,
            $work->title.' — Adilet-lift',
            $work->description ?: $work->title,
            route('works.show', $work),
            [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Наши работы', 'url' => route('works.index')],
                ['name' => $work->title, 'url' => route('works.show', $work)],
            ],
            $seo->workSchema($work),
        );

        return view('pages.works.show', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
            'work' => $work,
            'photosWord' => $this->photosWord($work->photos->count()),
        ]);
    }

    private function photosWord(int $count): string
    {
        $mod10 = $count % 10;
        $mod100 = $count % 100;

        if ($mod10 === 1 && $mod100 !== 11) {
            return 'фотография';
        }

        if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
            return 'фотографии';
        }

        return 'фотографий';
    }
}
