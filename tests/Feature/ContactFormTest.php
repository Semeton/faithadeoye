<?php

use App\Mail\ContactMessageReceived;
use App\Models\Message;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(SiteSettingSeeder::class);
    Mail::fake();
});

test('valid contact form submission stores message and queues mail', function () {
    Livewire::test('pages::home')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@example.com')
        ->set('contactSubject', 'Hello there')
        ->set('contactBody', 'This is a test message with enough length.')
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSet('messageSent', true);

    expect(Message::count())->toBe(1);
    $message = Message::first();
    expect($message->name)->toBe('Jane Doe');
    expect($message->email)->toBe('jane@example.com');
    expect($message->subject)->toBe('Hello there');
    expect($message->is_read)->toBeFalse();

    Mail::assertQueued(ContactMessageReceived::class);
});

test('contact form validates required fields', function () {
    Livewire::test('pages::home')
        ->call('sendMessage')
        ->assertHasErrors(['contactName', 'contactEmail', 'contactBody']);

    expect(Message::count())->toBe(0);
    Mail::assertNothingQueued();
});

test('contact form validates email format', function () {
    Livewire::test('pages::home')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'not-an-email')
        ->set('contactBody', 'This is a valid message body.')
        ->call('sendMessage')
        ->assertHasErrors(['contactEmail']);
});

test('contact form validates minimum body length', function () {
    Livewire::test('pages::home')
        ->set('contactName', 'Jane')
        ->set('contactEmail', 'jane@example.com')
        ->set('contactBody', 'Too short')
        ->call('sendMessage')
        ->assertHasErrors(['contactBody']);
});

test('contact form subject is optional', function () {
    Livewire::test('pages::home')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@example.com')
        ->set('contactBody', 'A sufficiently long message body for the contact form.')
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSet('messageSent', true);

    expect(Message::first()->subject)->toBeNull();
});
