<?php

use App\Models\User;
use App\Support\SiteSettings;

test('guests cannot update site settings', function () {
    $this->put(route('admin.settings.update'), [
        'company_name' => 'Adilet-lift',
    ])->assertRedirect(route('admin.login'));
});

test('an administrator can save contact and social links', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('admin.settings.update'), [
        'company_name' => 'Adilet-lift',
        'email' => 'hello@adilet-lift.test',
        'address' => 'Алматы',
        'whatsapp_url' => 'https://wa.me/77001234567',
        'instagram_url' => 'https://www.instagram.com/adilet.lift',
        'phones' => [
            ['label' => 'Офис', 'number' => '+7 (700) 123-45-67'],
        ],
    ])->assertRedirect();

    $this->assertDatabaseHas('settings', [
        'key' => 'instagram_url',
        'value' => 'https://www.instagram.com/adilet.lift',
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'whatsapp_url',
        'value' => 'https://wa.me/77001234567',
    ]);

    $this->get('/')
        ->assertSee('+7 (700) 123-45-67')
        ->assertSee('hello@adilet-lift.test')
        ->assertSee('Алматы')
        ->assertSee('https://wa.me/77001234567', false)
        ->assertSee('https://www.instagram.com/adilet.lift', false)
        ->assertDontSee('Направления');
});

test('an invalid instagram url is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.settings.edit'))
        ->put(route('admin.settings.update'), [
            'company_name' => 'Adilet-lift',
            'instagram_url' => 'not-a-url',
        ])
        ->assertRedirect(route('admin.settings.edit'))
        ->assertSessionHasErrors('instagram_url');
});

test('an incomplete settings phone number is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.settings.edit'))
        ->put(route('admin.settings.update'), [
            'company_name' => 'Adilet-lift',
            'phones' => [
                ['label' => 'Офис', 'number' => '+7 (700)'],
            ],
        ])
        ->assertRedirect(route('admin.settings.edit'))
        ->assertSessionHasErrors('phones.0.number');
});

test('the footer lists contacts instead of service directions', function () {
    app(SiteSettings::class)->put([
        'email' => 'hello@adilet-lift.test',
        'whatsapp_url' => 'https://wa.me/77001234567',
        'instagram_url' => 'https://www.instagram.com/adilet.lift',
        'phones' => [
            ['label' => 'Офис', 'number' => '+7 (700) 123-45-67'],
        ],
    ]);

    $this->get('/contacts')
        ->assertSee('Контакты')
        ->assertSee('+7 (700) 123-45-67')
        ->assertSee('WhatsApp')
        ->assertSee('Instagram')
        ->assertDontSee('Направления');
});

test('a higher map zoom produces a tighter embed bounding box', function () {
    $settings = app(SiteSettings::class);

    $settings->put([
        'map_lat' => '42.315713',
        'map_lng' => '69.588757',
        'map_zoom' => '8',
    ]);

    $wide = $settings->mapEmbedSrc();

    $settings->put(['map_zoom' => '16']);
    $close = $settings->mapEmbedSrc();

    parse_str(parse_url($wide, PHP_URL_QUERY) ?? '', $wideQuery);
    parse_str(parse_url($close, PHP_URL_QUERY) ?? '', $closeQuery);

    $wideParts = array_map('floatval', explode(',', (string) ($wideQuery['bbox'] ?? '')));
    $closeParts = array_map('floatval', explode(',', (string) ($closeQuery['bbox'] ?? '')));

    expect($wide)->not->toBe($close)
        ->and($wideParts[3] - $wideParts[1])->toBeGreaterThan(($closeParts[3] - $closeParts[1]) * 200);

    $this->get('/contacts')
        ->assertOk()
        ->assertSee('bbox=69.585042624397', false)
        ->assertSee('#map=16/', false);
});
