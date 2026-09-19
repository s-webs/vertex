<?php

namespace App\Support;

use Illuminate\Support\Str;

class UniqueSlug
{
    /**
     * @param  callable(string): bool  $exists
     */
    public static function make(string $source, callable $exists, ?string $fallbackPrefix = 'item'): string
    {
        $slug = Str::slug($source);

        if ($slug === '') {
            $slug = $fallbackPrefix.'-'.Str::lower(Str::random(8));
        }

        $base = $slug;
        $i = 2;

        while ($exists($slug)) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
