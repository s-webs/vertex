<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Support\SiteSettings;
use App\Support\StoresUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(SiteSettings $settings): View
    {
        return view('admin.settings.edit', [
            'values' => $settings->all(),
            'phones' => $settings->phones(),
        ]);
    }

    public function update(UpdateSettingsRequest $request, SiteSettings $settings, StoresUploads $uploads): RedirectResponse
    {
        $data = $request->safe()->except(['logo', 'logo_horizontal', 'favicon', 'phones']);
        $phones = [];

        foreach ($request->input('phones', []) as $phone) {
            if (filled($phone['number'] ?? null)) {
                $phones[] = [
                    'label' => (string) ($phone['label'] ?? 'Телефон'),
                    'number' => (string) $phone['number'],
                ];
            }
        }

        $data['phones'] = json_encode($phones, JSON_UNESCAPED_UNICODE);

        foreach (['logo' => 'logo_path', 'logo_horizontal' => 'logo_horizontal_path', 'favicon' => 'favicon_path'] as $file => $key) {
            if ($request->hasFile($file)) {
                $data[$key] = $uploads->replace($settings->get($key), $request->file($file), 'branding');
            }
        }

        $settings->put($data);

        return back()->with('status', 'Настройки сохранены.');
    }
}
