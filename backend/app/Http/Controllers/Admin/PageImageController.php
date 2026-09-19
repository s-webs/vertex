<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePageImageRequest;
use App\Models\PageImage;
use App\Support\StoresUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageImageController extends Controller
{
    public function index(): View
    {
        $records = PageImage::query()->get()->groupBy('page_key');
        $images = [];

        foreach ($records as $page => $items) {
            $images[$page] = $items->keyBy('slot_key');
        }

        return view('admin.page-images.index', [
            'slots' => config('site.page_images'),
            'images' => $images,
        ]);
    }

    public function update(UpdatePageImageRequest $request, StoresUploads $uploads): RedirectResponse
    {
        $pageKey = $request->validated('page_key');
        $slotKey = $request->validated('slot_key');
        $image = PageImage::query()->firstOrNew([
            'page_key' => $pageKey,
            'slot_key' => $slotKey,
        ]);

        $image->alt = $request->validated('alt');

        if ($request->hasFile('image')) {
            $image->path = $uploads->replace($image->path, $request->file('image'), 'pages');
        }

        if (blank($image->path)) {
            return back()->withErrors(['image' => 'Загрузите изображение для слота.']);
        }

        $image->save();

        return back()->with('status', 'Изображение сохранено.');
    }
}
