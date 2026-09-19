<?php

use App\Models\User;

test('guests are redirected from the admin dashboard', function () {
    $this->get('/admin')->assertRedirect(route('admin.login'));
});

test('an administrator can sign in', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('invalid credentials do not authenticate', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->from('/admin/login')->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ])->assertRedirect('/admin/login')->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login is rate limited', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->from('/admin/login')->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect('/admin/login');
    }

    $this->from('/admin/login')->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(429);
});
