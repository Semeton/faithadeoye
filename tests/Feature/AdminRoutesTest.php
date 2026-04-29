<?php

use App\Models\User;

test('unauthenticated requests to admin routes redirect to login', function () {
    $paths = ['/admin', '/admin/content', '/admin/projects', '/admin/milestones', '/admin/impact-areas', '/admin/messages', '/admin/analytics'];

    foreach ($paths as $path) {
        $this->get($path)->assertRedirect(route('login'));
    }
});

test('authenticated admin can access dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertOk();
});

test('authenticated admin can access content page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/content')->assertOk();
});

test('authenticated admin can access projects page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/projects')->assertOk();
});

test('authenticated admin can access milestones page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/milestones')->assertOk();
});

test('authenticated admin can access impact areas page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/impact-areas')->assertOk();
});

test('authenticated admin can access messages page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/messages')->assertOk();
});

test('authenticated admin can access analytics page', function () {
    $this->actingAs(User::factory()->create())->get('/admin/analytics')->assertOk();
});
