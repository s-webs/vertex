<?php

use App\Models\Redirect;
use App\Models\User;

test('a stored redirect returns the configured status', function () {
    Redirect::factory()->create([
        'from_path' => '/old-about',
        'to_path' => '/about',
        'status' => 301,
    ]);

    $this->get('/old-about')->assertRedirect('/about')->assertStatus(301);
});

test('an administrator can create a redirect', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/admin/redirects', [
        'from_path' => 'legacy',
        'to_path' => 'catalog',
        'status' => 301,
    ])->assertRedirect();

    $this->assertDatabaseHas('redirects', [
        'from_path' => '/legacy',
        'to_path' => '/catalog',
        'status' => 301,
    ]);

    $this->get('/legacy')->assertRedirect('/catalog');
});
