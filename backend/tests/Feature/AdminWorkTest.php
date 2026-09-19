<?php

use App\Models\User;
use App\Models\Work;

test('guests cannot edit works', function () {
    $work = Work::factory()->create(['slug' => 'residential']);

    $this->get(route('admin.works.edit', $work))
        ->assertRedirect(route('admin.login'));
});

test('an administrator can open a work edit page by id', function () {
    $user = User::factory()->create();
    $work = Work::factory()->create(['slug' => 'residential']);

    $this->actingAs($user)
        ->get('/admin/works/'.$work->id.'/edit')
        ->assertOk()
        ->assertSee($work->title);
});

test('updating a work slug redirects to the new edit url', function () {
    $user = User::factory()->create();
    $work = Work::factory()->create([
        'slug' => 'residential',
        'title' => 'Жилой комплекс',
    ]);

    $this->actingAs($user)
        ->from(route('admin.works.edit', $work))
        ->put(route('admin.works.update', $work), [
            'number' => 'ОБЪЕКТ 01',
            'title' => 'ЖК Beknur Deluxe',
            'slug' => 'zk-beknur-deluxe',
            'description' => 'Обновлённое описание',
            'meta' => 'Жилой объект',
            'sort_order' => 1,
            'is_published' => '1',
        ])
        ->assertRedirect(route('admin.works.edit', $work->fresh()));

    $work->refresh();

    expect($work->slug)->toBe('zk-beknur-deluxe');

    $this->actingAs($user)
        ->get(route('admin.works.edit', $work))
        ->assertOk()
        ->assertSee('ЖК Beknur Deluxe');

    $this->get('/admin/works/residential/edit')->assertNotFound();
    $this->get(route('works.show', $work))->assertOk();
});
