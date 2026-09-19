<?php

namespace App\Support;

use App\Models\PageImage;

class PageImages
{
    public function find(string $page, string $slot): ?PageImage
    {
        return PageImage::query()
            ->where('page_key', $page)
            ->where('slot_key', $slot)
            ->first();
    }

    public function url(string $page, string $slot, ?string $fallback = null): ?string
    {
        return Media::url($this->find($page, $slot)?->path) ?? $fallback;
    }

    public function alt(string $page, string $slot, string $fallback = ''): string
    {
        $alt = $this->find($page, $slot)?->alt;

        return filled($alt) ? $alt : $fallback;
    }
}
