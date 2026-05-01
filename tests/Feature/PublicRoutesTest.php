<?php

use App\Models\Project;
use Database\Seeders\SiteSettingSeeder;

beforeEach(function () {
    $this->seed(SiteSettingSeeder::class);
});

test('homepage returns 200', function () {
    $this->get(route('home'))->assertOk();
});

test('projects page returns 200', function () {
    $this->get(route('projects'))->assertOk();
});

test('projects page shows published projects', function () {
    Project::factory()->create(['published' => true, 'title' => 'Published Project']);
    Project::factory()->create(['published' => false, 'title' => 'Hidden Project']);

    $response = $this->get(route('projects'));

    $response->assertOk()->assertSee('Published Project')->assertDontSee('Hidden Project');
});

test('project detail returns 200 for published project', function () {
    Project::factory()->create(['published' => true, 'slug' => 'my-test-project']);

    $this->get(route('project.show', 'my-test-project'))->assertOk();
});

test('project detail returns 404 for unpublished project', function () {
    Project::factory()->create(['published' => false, 'slug' => 'draft-project']);

    $this->get(route('project.show', 'draft-project'))->assertNotFound();
});

test('project detail returns 404 for nonexistent slug', function () {
    $this->get(route('project.show', 'does-not-exist'))->assertNotFound();
});

test('404 page renders custom view', function () {
    $this->get('/this-does-not-exist')
        ->assertNotFound()
        ->assertSee("This page doesn't exist.", false);
});
