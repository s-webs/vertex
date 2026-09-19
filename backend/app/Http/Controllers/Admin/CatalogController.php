<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SeoOwnerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCatalogRequest;
use App\Http\Requests\Admin\UpdateCatalogRequest;
use App\Models\Catalog;
use App\Models\SeoMeta;
use App\Support\SavesSeoMeta;
use App\Support\StoresUploads;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        return view('admin.catalogs.index', [
            'catalogs' => Catalog::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.catalogs.form');
    }

    public function store(StoreCatalogRequest $request, StoresUploads $uploads, SavesSeoMeta $seo): RedirectResponse
    {
        $catalog = $this->persist(new Catalog, $request->safe()->all(), $request, $uploads, $seo, null);

        return redirect()
            ->route('admin.catalogs.edit', $catalog)
            ->with('status', 'Каталог создан.');
    }

    public function edit(Catalog $catalog): View
    {
        return view('admin.catalogs.form', [
            'catalog' => $catalog,
            'meta' => SeoMeta::findFor(SeoOwnerType::Catalog, (string) $catalog->id),
        ]);
    }

    public function update(UpdateCatalogRequest $request, Catalog $catalog, StoresUploads $uploads, SavesSeoMeta $seo): RedirectResponse
    {
        $this->persist($catalog, $request->safe()->all(), $request, $uploads, $seo, $catalog->id);

        return redirect()
            ->route('admin.catalogs.edit', $catalog)
            ->with('status', 'Каталог обновлён.');
    }

    public function destroy(Catalog $catalog, StoresUploads $uploads): RedirectResponse
    {
        $uploads->delete($catalog->cover_path);
        $uploads->delete($catalog->pdf_path);
        SeoMeta::query()
            ->where('owner_type', SeoOwnerType::Catalog)
            ->where('owner_key', (string) $catalog->id)
            ->delete();
        $catalog->delete();

        return redirect()->route('admin.catalogs.index')->with('status', 'Каталог удалён.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function persist(
        Catalog $catalog,
        array $data,
        StoreCatalogRequest|UpdateCatalogRequest $request,
        StoresUploads $uploads,
        SavesSeoMeta $seo,
        ?int $ignoreId,
    ): Catalog {
        $catalog->fill([
            'brand' => $data['brand'],
            'year' => $data['year'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $this->tags($data['tags'] ?? ''),
            'page_count' => (int) ($data['page_count'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => $request->boolean('is_published'),
            'slug' => UniqueSlug::make(
                filled($data['slug'] ?? null) ? $data['slug'] : $data['title'],
                fn (string $slug): bool => Catalog::query()
                    ->where('slug', $slug)
                    ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                    ->exists(),
                'catalog',
            ),
        ]);

        if ($request->hasFile('cover')) {
            $catalog->cover_path = $uploads->replace($catalog->cover_path, $request->file('cover'), 'catalogs');
        }

        if ($request->hasFile('pdf')) {
            $catalog->pdf_path = $uploads->replace($catalog->pdf_path, $request->file('pdf'), 'catalogs');
        }

        $catalog->save();
        $seo->save(SeoOwnerType::Catalog, (string) $catalog->id, $data, $uploads, $request);

        return $catalog;
    }

    /**
     * @return list<string>
     */
    private function tags(?string $tags): array
    {
        return collect(explode(',', (string) $tags))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->values()
            ->all();
    }
}
