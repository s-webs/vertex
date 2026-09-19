<?php

namespace App\Support;

readonly class SeoDocument
{
    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @param  array<string, mixed>|null  $entitySchema
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public string $robots,
        public string $ogTitle,
        public string $ogDescription,
        public ?string $ogImage,
        public array $breadcrumbs = [],
        public ?array $entitySchema = null,
    ) {}
}
