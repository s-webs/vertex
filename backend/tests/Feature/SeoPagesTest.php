<?php

use App\Enums\SeoOwnerType;
use App\Models\SeoMeta;
use App\Models\Work;

test('the home page renders seo tags', function () {
    SeoMeta::factory()->create([
        'owner_type' => SeoOwnerType::Page,
        'owner_key' => 'home',
        'title' => 'Лифты Adilet-lift',
        'description' => 'Установка и сервис лифтов.',
        'robots' => 'index,follow',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('<title>Лифты Adilet-lift</title>', false)
        ->assertSee('name="description" content="Установка и сервис лифтов."', false)
        ->assertSee('rel="canonical"', false)
        ->assertSee('application/ld+json', false);
});

test('the sitemap includes published works only', function () {
    $published = Work::factory()->create(['slug' => 'visible-work', 'is_published' => true]);
    Work::factory()->unpublished()->create(['slug' => 'hidden-work']);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee(route('home'), false)
        ->assertSee(route('works.show', $published), false)
        ->assertDontSee('hidden-work');
});

test('robots.txt disallows the admin area', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /admin')
        ->assertSee('Sitemap:');
});
