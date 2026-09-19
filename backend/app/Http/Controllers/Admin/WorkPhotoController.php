<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWorkPhotoRequest;
use App\Models\Work;
use App\Models\WorkPhoto;
use App\Support\StoresUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkPhotoController extends Controller
{
    public function store(StoreWorkPhotoRequest $request, Work $work, StoresUploads $uploads): RedirectResponse
    {
        $max = (int) $work->photos()->max('sort_order');

        $work->photos()->create([
            'path' => $uploads->store($request->file('photo'), 'works'),
            'alt' => $request->validated('alt'),
            'caption' => $request->validated('caption'),
            'sort_order' => $max + 1,
        ]);

        return back()->with('status', 'Фото добавлено.');
    }

    public function move(Request $request, Work $work, WorkPhoto $photo): RedirectResponse
    {
        abort_unless($photo->work_id === $work->id, 404);

        $direction = $request->string('direction')->toString();
        $photos = $work->photos()->ordered()->get();
        $index = $photos->search(fn (WorkPhoto $item): bool => $item->id === $photo->id);

        if ($index === false) {
            return back();
        }

        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;

        if (! isset($photos[$swapWith])) {
            return back();
        }

        $currentOrder = $photos[$index]->sort_order;
        $photos[$index]->update(['sort_order' => $photos[$swapWith]->sort_order]);
        $photos[$swapWith]->update(['sort_order' => $currentOrder]);

        return back();
    }

    public function destroy(Work $work, WorkPhoto $photo, StoresUploads $uploads): RedirectResponse
    {
        abort_unless($photo->work_id === $work->id, 404);
        $uploads->delete($photo->path);
        $photo->delete();

        return back()->with('status', 'Фото удалено.');
    }
}
