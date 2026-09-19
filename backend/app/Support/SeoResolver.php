<?php

namespace App\Support;

use App\Enums\SeoOwnerType;
use App\Models\SeoMeta;
use App\Models\Work;

class SeoResolver
{
    public function __construct(private SiteSettings $settings) {}

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @param  array<string, mixed>|null  $entitySchema
     */
    public function forPage(
        string $key,
        string $fallbackTitle,
        string $fallbackDescription,
        string $canonicalUrl,
        array $breadcrumbs = [],
        ?array $entitySchema = null,
    ): SeoDocument {
        return $this->document(
            SeoMeta::findFor(SeoOwnerType::Page, $key),
            $fallbackTitle,
            $fallbackDescription,
            $canonicalUrl,
            $breadcrumbs,
            $entitySchema,
        );
    }

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @param  array<string, mixed>|null  $entitySchema
     */
    public function forOwner(
        SeoOwnerType $type,
        string $ownerKey,
        string $fallbackTitle,
        string $fallbackDescription,
        string $canonicalUrl,
        array $breadcrumbs = [],
        ?array $entitySchema = null,
    ): SeoDocument {
        return $this->document(
            SeoMeta::findFor($type, $ownerKey),
            $fallbackTitle,
            $fallbackDescription,
            $canonicalUrl,
            $breadcrumbs,
            $entitySchema,
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function jsonLd(SeoDocument $seo): array
    {
        $graph = [$this->organizationSchema(), $this->localBusinessSchema()];

        if ($seo->breadcrumbs !== []) {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($seo->breadcrumbs)->values()->map(fn (array $crumb, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ])->all(),
            ];
        }

        if ($seo->entitySchema !== null) {
            $graph[] = $seo->entitySchema;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function workSchema(Work $work): array
    {
        $photos = $work->photos
            ->map(fn ($photo) => Media::url($photo->path))
            ->filter()
            ->values()
            ->all();

        return array_filter([
            '@type' => 'CreativeWork',
            'name' => $work->title,
            'description' => $work->description,
            'url' => route('works.show', $work),
            'image' => $photos === [] ? null : $photos,
        ]);
    }

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @param  array<string, mixed>|null  $entitySchema
     */
    private function document(
        ?SeoMeta $meta,
        string $fallbackTitle,
        string $fallbackDescription,
        string $canonicalUrl,
        array $breadcrumbs,
        ?array $entitySchema,
    ): SeoDocument {
        $title = filled($meta?->title) ? $meta->title : $fallbackTitle;
        $description = filled($meta?->description) ? $meta->description : $fallbackDescription;

        return new SeoDocument(
            title: $title,
            description: $description,
            canonical: filled($meta?->canonical) ? $meta->canonical : $canonicalUrl,
            robots: filled($meta?->robots) ? $meta->robots : 'index,follow',
            ogTitle: filled($meta?->og_title) ? $meta->og_title : $title,
            ogDescription: filled($meta?->og_description) ? $meta->og_description : $description,
            ogImage: Media::url($meta?->og_image) ?? $this->settings->ogImageUrl(),
            breadcrumbs: $breadcrumbs,
            entitySchema: $entitySchema,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationSchema(): array
    {
        return array_filter([
            '@type' => 'Organization',
            'name' => $this->settings->companyName(),
            'url' => url('/'),
            'logo' => $this->settings->logoUrl(),
            'email' => $this->settings->email(),
            'telephone' => $this->settings->primaryPhone()['number'] ?? null,
            'sameAs' => $this->settings->socialProfileUrls() ?: null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function localBusinessSchema(): array
    {
        $schema = [
            '@type' => 'LocalBusiness',
            'name' => $this->settings->companyName(),
            'url' => url('/'),
            'image' => $this->settings->ogImageUrl() ?? $this->settings->logoUrl(),
            'email' => $this->settings->email(),
            'telephone' => $this->settings->primaryPhone()['number'] ?? null,
            'address' => $this->settings->address() ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->settings->address(),
            ] : null,
        ];

        if ($this->settings->hasMapPin()) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->settings->mapLat(),
                'longitude' => $this->settings->mapLng(),
            ];
        }

        return array_filter($schema);
    }
}
