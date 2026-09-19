<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRedirectRequest;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(): View
    {
        return view('admin.redirects.index', [
            'redirects' => Redirect::query()->latest()->get(),
        ]);
    }

    public function store(StoreRedirectRequest $request): RedirectResponse
    {
        Redirect::query()->create($request->validated());

        return back()->with('status', 'Редирект добавлен.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return back()->with('status', 'Редирект удалён.');
    }
}
