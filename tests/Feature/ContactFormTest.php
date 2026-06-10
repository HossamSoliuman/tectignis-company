<?php

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('valid contact form submission creates a lead', function () {
    $this->post(route('contact.submit'), [
        'con_name' => 'Test User',
        'con_email' => 'test@example.com',
        'con_phone' => '9876543210',
        'con_message' => 'Hello, I would like to inquire about your services.',
    ])->assertRedirect();

    $this->assertDatabaseHas('leads', [
        'email' => 'test@example.com',
        'source' => 'contact',
    ]);

    expect(Lead::count())->toBe(1);
});

it('contact form with missing required fields returns validation errors', function () {
    $this->post(route('contact.submit'), [])
        ->assertSessionHasErrors(['con_name', 'con_email', 'con_message']);
});

it('contact form with invalid email returns validation error', function () {
    $this->post(route('contact.submit'), [
        'con_name' => 'Test User',
        'con_email' => 'not-an-email',
        'con_message' => 'Hello',
    ])->assertSessionHasErrors(['con_email']);
});

it('consultation modal submission without email creates a consultation lead', function () {
    $this->post(route('contact.submit'), [
        'con_source' => 'consultation',
        'con_name' => 'Modal User',
        'con_phone' => '9876543210',
        'con_subject' => 'Cloud Solutions',
        'con_message' => 'Please call me about cloud migration.',
    ])->assertRedirect()->assertSessionHas('consultation_status');

    $this->assertDatabaseHas('leads', [
        'name' => 'Modal User',
        'email' => null,
        'source' => 'consultation',
    ]);
});

it('consultation modal submission requires a mobile number', function () {
    $this->post(route('contact.submit'), [
        'con_source' => 'consultation',
        'con_name' => 'Modal User',
        'con_message' => 'Please call me.',
    ])->assertSessionHasErrors(['con_phone']);
});
