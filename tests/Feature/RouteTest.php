<?php

use App\Option;
use App\Role;
use App\User;
use Database\Seeders\OptionSeeder;
use Tests\TestCasManager;

beforeEach(function () {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('can get /', function () {
    $response = $this->get('/');

    $response->assertRedirect('login');
});

it('shows first run setup', function () {
    $option = Option::where('name', 'first_run')->first();
    $option->value = 1;
    $option->save();
    $response = $this->get('/');

    $response->assertRedirect('/setup');

    $this->get('/setup')->assertOk();
});

it('cannot get /setup after first run setup', function () {
    $response = $this->get('/setup');

    $response->assertRedirect('/');
});

it('gets /admin/login without logging in', function ($url) {
    $response = $this->get($url);

    $response->assertRedirect('login');
})->with('admin_routes');

it('cannot access the admin backend as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

it('cannot access the admin backend as user with app specific permission:', function ($access) {
    $user = User::factory()->user()->create();
    $role = Role::factory()->state(['name' => $access])->count(1)->create();
    $user->roles()->attach($role);
    $user->save();

    $this->actingAs($user)->get('/admin')->assertForbidden();
})->with('app_permissions');

it('can access the admin backend as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertOk();
});
