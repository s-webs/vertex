<?php

namespace App\Models;

use App\Enums\SeoOwnerType;
use Database\Factories\WorkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'slug',
    'number',
    'title',
    'description',
    'meta',
    'sort_order',
    'is_published',
])]
class Work extends Model
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(WorkPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(SeoMeta::class, 'owner_key', 'id')
            ->where('owner_type', SeoOwnerType::Work->value);
    }

    /**
     * @return list<string>
     */
    public function metaList(): array
    {
        return array_values(array_filter($this->meta ?? []));
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
