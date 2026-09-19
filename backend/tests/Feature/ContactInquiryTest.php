<?php

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use App\Models\User;

function openContactForm(): void
{
    test()->get('/contacts')->assertOk();
    test()->travel((int) config('site.inquiry.min_seconds', 3) + 1)->seconds();
}

test('a visitor can submit a contact inquiry', function () {
    openContactForm();

    $this->from('/contacts')->post('/contacts', [
        'name' => 'Алия',
        'phone' => '+7 (700) 123-45-67',
        'service' => 'Установка нового лифта',
        'message' => 'Нужен лифт на 9 этажей.',
        'website' => '',
    ])->assertRedirect(route('contacts.show'))->assertSessionHas('status');

    $this->assertDatabaseHas('inquiries', [
        'name' => 'Алия',
        'phone' => '+7 (700) 123-45-67',
        'status' => InquiryStatus::New->value,
    ]);
});

test('the contact form validates required fields', function () {
    openContactForm();

    $this->from('/contacts')->post('/contacts', [])
        ->assertRedirect('/contacts')
        ->assertSessionHasErrors(['name', 'phone', 'service']);
});

test('an incomplete phone number is rejected', function () {
    openContactForm();

    $this->from('/contacts')->post('/contacts', [
        'name' => 'Алия',
        'phone' => '+7 (700) 12',
        'service' => 'Установка нового лифта',
        'website' => '',
    ])->assertRedirect('/contacts')->assertSessionHasErrors('phone');
});

test('a filled honeypot field is rejected', function () {
    openContactForm();

    $this->from('/contacts')->post('/contacts', [
        'name' => 'Алия',
        'phone' => '+7 (700) 123-45-67',
        'service' => 'Установка нового лифта',
        'website' => 'https://spam.example',
    ])->assertRedirect('/contacts')->assertSessionHasErrors('website');

    $this->assertDatabaseCount('inquiries', 0);
});

test('an instant bot submission is rejected', function () {
    $this->get('/contacts')->assertOk();

    $this->from('/contacts')->post('/contacts', [
        'name' => 'Алия',
        'phone' => '+7 (700) 123-45-67',
        'service' => 'Установка нового лифта',
        'website' => '',
    ])->assertRedirect('/contacts')->assertSessionHasErrors('form');

    $this->assertDatabaseCount('inquiries', 0);
});

test('a duplicate inquiry from the same phone is rejected briefly', function () {
    openContactForm();

    $payload = [
        'name' => 'Алия',
        'phone' => '+7 (700) 123-45-67',
        'service' => 'Установка нового лифта',
        'website' => '',
    ];

    $this->from('/contacts')->post('/contacts', $payload)->assertRedirect(route('contacts.show'));

    openContactForm();

    $this->from('/contacts')->post('/contacts', $payload)
        ->assertRedirect('/contacts')
        ->assertSessionHasErrors('phone');

    $this->assertDatabaseCount('inquiries', 1);
});

test('contact submissions are rate limited', function () {
    $limit = (int) config('site.inquiry.per_minute', 5);

    for ($i = 0; $i <= $limit; $i++) {
        openContactForm();

        $response = $this->post('/contacts', [
            'name' => 'Алия',
            'phone' => sprintf('+7 (700) %03d-11-11', $i),
            'service' => 'Установка нового лифта',
            'website' => '',
        ]);

        if ($i < $limit) {
            $response->assertRedirect(route('contacts.show'));
        } else {
            $response->assertStatus(429);
        }
    }
});

test('guests cannot view inquiries in the admin', function () {
    Inquiry::factory()->create();

    $this->get('/admin/inquiries')->assertRedirect(route('admin.login'));
});

test('an administrator can mark an inquiry as read', function () {
    $user = User::factory()->create();
    $inquiry = Inquiry::factory()->create(['status' => InquiryStatus::New]);

    $this->actingAs($user)
        ->put(route('admin.inquiries.read', $inquiry))
        ->assertRedirect();

    expect($inquiry->fresh()->status)->toBe(InquiryStatus::Read);
});

test('guests cannot delete inquiries', function () {
    $inquiry = Inquiry::factory()->create();

    $this->delete(route('admin.inquiries.destroy', $inquiry))
        ->assertRedirect(route('admin.login'));

    $this->assertDatabaseHas('inquiries', ['id' => $inquiry->id]);
});

test('an administrator can delete an inquiry', function () {
    $user = User::factory()->create();
    $inquiry = Inquiry::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.inquiries.destroy', $inquiry))
        ->assertRedirect(route('admin.inquiries.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
});
