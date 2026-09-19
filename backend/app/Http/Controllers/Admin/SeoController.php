<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SeoOwnerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSeoRequest;
use App\Models\SeoMeta;
use App\Support\SavesSeoMeta;
use App\Support\SiteSettings;
use App\Support\StoresUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function index(SiteSettings $settings): View
    {
        $pages = config('site.pages');
        $metas = SeoMeta::query()
            ->where('owner_type', SeoOwnerType::Page)
            ->get()
            ->keyBy('owner_key');

        return view('admin.seo.index', [
            'pages' => $pages,
            'metas' => $metas,
            'values' => $settings->all(),
        ]);
    }

    public function edit(string $page): View
    {
        $pages = config('site.pages');
        abort_unless(isset($pages[$page]), 404);

        return view('admin.seo.edit', [
            'pageKey' => $page,
            'page' => $pages[$page],
            'meta' => SeoMeta::findFor(SeoOwnerType::Page, $page),
        ]);
    }

    public function update(UpdateSeoRequest $request, string $page, SavesSeoMeta $seo, StoresUploads $uploads): RedirectResponse
    {
        abort_unless(isset(config('site.pages')[$page]), 404);
        $seo->save(SeoOwnerType::Page, $page, $request->validated(), $uploads, $request);

        return back()->with('status', 'SEO страницы сохранён.');
    }

    public function updateGlobals(Request $request, SiteSettings $settings, StoresUploads $uploads): RedirectResponse
    {
        $data = $request->validate([
            'google_site_verification' => ['nullable', 'string', 'max:255'],
            'yandex_verification' => ['nullable', 'string', 'max:255'],
            'robots_extra' => ['nullable', 'string', 'max:2000'],
            'og_image' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['og_image']);

        if ($request->hasFile('og_image')) {
            $data['og_image_path'] = $uploads->replace($settings->get('og_image_path'), $request->file('og_image'), 'seo');
        }

        $settings->put($data);

        return back()->with('status', 'Глобальные SEO-настройки сохранены.');
    }
}
