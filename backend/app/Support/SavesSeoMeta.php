<?php

namespace App\Support;

use App\Enums\SeoOwnerType;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SavesSeoMeta
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function save(SeoOwnerType $type, string $key, array $payload, ?StoresUploads $uploads = null, ?Request $request = null): SeoMeta
    {
        $meta = SeoMeta::query()->firstOrNew([
            'owner_type' => $type,
            'owner_key' => $key,
        ]);

        $meta->fill([
            'title' => $payload['seo_title'] ?? null,
            'description' => $payload['seo_description'] ?? null,
            'og_title' => $payload['og_title'] ?? null,
            'og_description' => $payload['og_description'] ?? null,
            'canonical' => $payload['canonical'] ?? null,
            'robots' => filled($payload['robots'] ?? null) ? $payload['robots'] : 'index,follow',
        ]);

        if ($request?->boolean('remove_og_image')) {
            $uploads?->delete($meta->og_image);
            $meta->og_image = null;
        }

        if ($request?->hasFile('og_image')) {
            $meta->og_image = $uploads->replace($meta->og_image, $request->file('og_image'), 'seo');
        }

        $meta->save();

        return $meta;
    }
}
