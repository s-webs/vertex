<?php

namespace App\Models;

use App\Enums\SeoOwnerType;
use Database\Factories\CatalogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'slug',
    'brand',
    'year',
    'title',
    'description',
    'tags',
    'cover_path',
    'pdf_path',
    'page_count',
    'sort_order',
    'is_published',
])]
class Catalog extends Model
{
    /** @use HasFactory<CatalogFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
            'page_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function seo(): HasOne
    {
        return $this->hasOne(SeoMeta::class, 'owner_key', 'id')
            ->where('owner_type', SeoOwnerType::Catalog->value);
    }

    /**
     * @return list<string>
     */
    public function tagList(): array
    {
        return array_values(array_filter($this->tags ?? []));
    }

    public function makerLabel(): string
    {
        return collect([$this->brand, $this->year])->filter()->implode(' · ');
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
