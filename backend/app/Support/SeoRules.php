<?php

namespace App\Support;

class SeoRules
{
    /**
     * @return array<string, mixed>
     */
    public static function fields(): array
    {
        return [
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:180'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:180'],
            'canonical' => ['nullable', 'url', 'max:2048'],
            'robots' => ['nullable', 'string', 'max:80'],
            'og_image' => ['nullable', 'image', 'max:5120'],
            'remove_og_image' => ['sometimes', 'boolean'],
        ];
    }
}
