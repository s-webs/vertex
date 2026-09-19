<?php

use App\Models\Work;
use App\Models\WorkPhoto;

test('public pages return successful responses', function () {
    foreach (['/', '/catalog', '/about', '/works', '/contacts'] as $uri) {
        $this->get($uri)->assertOk();
    }
});

test('a published work is visible', function () {
    $work = Work::factory()->create([
        'slug' => 'residential-tower',
        'title' => 'Жилой комплекс',
        'is_published' => true,
    ]);
    WorkPhoto::factory()->create(['work_id' => $work->id]);

    $this->get(route('works.show', $work))
        ->assertOk()
        ->assertSee('Жилой комплекс');
});

test('an unpublished work returns not found', function () {
    $work = Work::factory()->unpublished()->create([
        'slug' => 'hidden-object',
    ]);

    $this->get('/works/'.$work->slug)->assertNotFound();
});
