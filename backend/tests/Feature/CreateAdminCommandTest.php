<?php

use App\Models\User;

test('the command creates an administrator with a password that contains special characters', function () {
    $password = 'P@ss w0rd!#^&';

    $this->artisan('admin:create', [
        'name' => 'Главный админ',
        'email' => 'owner@example.com',
        'password' => $password,
    ])->assertSuccessful();

    $user = User::query()->where('email', 'owner@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Главный админ');

    $this->post('/admin/login', [
        'email' => 'owner@example.com',
        'password' => $password,
    ])->assertRedirect(route('admin.dashboard'));
});

test('the command rejects a duplicate email unless update is requested', function () {
    User::factory()->create([
        'email' => 'owner@example.com',
        'password' => 'old-password',
    ]);

    $this->artisan('admin:create', [
        'name' => 'Другой',
        'email' => 'owner@example.com',
        'password' => 'new-password',
    ])->assertFailed();

    $this->artisan('admin:create', [
        'name' => 'Другой',
        'email' => 'owner@example.com',
        'password' => 'new-password',
        '--update' => true,
    ])->assertSuccessful();

    $this->post('/admin/login', [
        'email' => 'owner@example.com',
        'password' => 'new-password',
    ])->assertRedirect(route('admin.dashboard'));
});
