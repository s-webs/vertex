<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SeoOwnerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWorkRequest;
use App\Http\Requests\Admin\UpdateWorkRequest;
use App\Models\SeoMeta;
use App\Models\Work;
use App\Support\SavesSeoMeta;
use App\Support\StoresUploads;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function index(): View
    {
        return view('admin.works.index', [
            'works' => Work::query()->ordered()->withCount('photos')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.works.form');
    }

    public function store(StoreWorkRequest $request, StoresUploads $uploads, SavesSeoMeta $seo): RedirectResponse
    {
        $work = $this->persist(new Work, $request->safe()->all(), $request, $uploads, $seo, null);

        return redirect()
            ->route('admin.works.edit', $work)
            ->with('status', 'Объект создан.');
    }

    public function edit(Work $work): View
    {
        $work->load('photos');

        return view('admin.works.form', [
            'work' => $work,
            'meta' => SeoMeta::findFor(SeoOwnerType::Work, (string) $work->id),
        ]);
    }

    public function update(UpdateWorkRequest $request, Work $work, StoresUploads $uploads, SavesSeoMeta $seo): RedirectResponse
    {
        $this->persist($work, $request->safe()->all(), $request, $uploads, $seo, $work->id);

        return redirect()
            ->route('admin.works.edit', $work)
            ->with('status', 'Объект обновлён.');
    }

    public function destroy(Work $work, StoresUploads $uploads): RedirectResponse
    {
        foreach ($work->photos as $photo) {
            $uploads->delete($photo->path);
        }

        SeoMeta::query()
            ->where('owner_type', SeoOwnerType::Work)
            ->where('owner_key', (string) $work->id)
            ->delete();
        $work->delete();

        return redirect()->route('admin.works.index')->with('status', 'Объект удалён.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function persist(
        Work $work,
        array $data,
        StoreWorkRequest|UpdateWorkRequest $request,
        StoresUploads $uploads,
        SavesSeoMeta $seo,
        ?int $ignoreId,
    ): Work {
        $number = $data['number'] ?? '';

        if ($number === '') {
            $number = 'ОБЪЕКТ '.str_pad((string) (Work::query()->count() + ($ignoreId ? 0 : 1)), 2, '0', STR_PAD_LEFT);
        }

        $work->fill([
            'number' => $number,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'meta' => collect(explode(',', (string) ($data['meta'] ?? '')))
                ->map(fn (string $item): string => trim($item))
                ->filter()
                ->values()
                ->all(),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => $request->boolean('is_published'),
            'slug' => UniqueSlug::make(
                filled($data['slug'] ?? null) ? $data['slug'] : $data['title'],
                fn (string $slug): bool => Work::query()
                    ->where('slug', $slug)
                    ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                    ->exists(),
                'work',
            ),
        ]);
        $work->save();
        $seo->save(SeoOwnerType::Work, (string) $work->id, $data, $uploads, $request);

        return $work;
    }
}
