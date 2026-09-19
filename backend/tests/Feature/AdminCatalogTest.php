<?php

use App\Models\Catalog;
use App\Models\User;

test('guests cannot create catalogs', function () {
    $this->post('/admin/catalogs', [
        'brand' => 'SIGE',
        'title' => 'Каталог',
    ])->assertRedirect(route('admin.login'));

    $this->assertDatabaseCount('catalogs', 0);
});

test('an administrator can create a catalog', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/admin/catalogs', [
        'brand' => 'SIGE',
        'title' => 'Пассажирские лифты',
        'description' => 'Полная линейка.',
        'tags' => 'Пассажирские, Сервис',
        'page_count' => 30,
        'is_published' => '1',
        'seo_title' => 'Каталог SIGE',
        'seo_description' => 'Каталог пассажирских лифтов SIGE.',
    ])->assertRedirect();

    $catalog = Catalog::query()->first();

    expect($catalog)->not->toBeNull()
        ->and($catalog->brand)->toBe('SIGE')
        ->and($catalog->is_published)->toBeTrue()
        ->and($catalog->tagList())->toContain('Пассажирские');

    $this->get('/catalog')->assertOk()->assertSee('Пассажирские лифты');
});
