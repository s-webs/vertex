<?php

namespace App\Models;

use App\Enums\SeoOwnerType;
use Database\Factories\SeoMetaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'owner_type',
    'owner_key',
    'title',
    'description',
    'og_title',
    'og_description',
    'og_image',
    'canonical',
    'robots',
])]
class SeoMeta extends Model
{
    /** @use HasFactory<SeoMetaFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'owner_type' => SeoOwnerType::class,
        ];
    }

    public static function findFor(SeoOwnerType $type, string $key): ?self
    {
        return self::query()
            ->where('owner_type', $type)
            ->where('owner_key', $key)
            ->first();
    }
}
