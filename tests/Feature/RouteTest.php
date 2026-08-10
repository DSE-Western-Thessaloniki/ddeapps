<?php

use App\Option;
use App\Role;
use App\User;
use Database\Seeders\OptionSeeder;

beforeEach(function (): void {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('can get /', function (): void {
    $response = $this->get('/');

    $response->assertRedirect('login');
});

it('shows first run setup', function (): void {
    $option = Option::where('name', 'first_run')->first();
    $option->value = 1;
    $option->save();
    $response = $this->get('/');

    $response->assertRedirect('/setup');

    $this->get('/setup')->assertOk();
});

it('cannot get /setup after first run setup', function (): void {
    $response = $this->get('/setup');

    $response->assertRedirect('/');
});

it('gets /admin/login without logging in', function ($url): void {
    $response = $this->get($url);

    $response->assertRedirect('login');
})->with('admin_routes');

it('cannot access the admin backend as user', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

it('cannot access the admin backend as user with app specific permission:', function ($access): void {
    $user = User::factory()->user()->create();
    $role = Role::factory()->state(['name' => $access])->count(1)->create();
    $user->roles()->attach($role);
    $user->save();

    $this->actingAs($user)->get('/admin')->assertForbidden();
})->with('app_permissions');

it('can access the admin backend as admin', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('logout redirects to login', function (): void {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->user()->create();
    $user->active = 1;
    $user->save();

    $response = $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ]);
    $response->assertRedirect(route('home'));
    $response = $this->post('/logout');
    $response->assertStatus(302)->assertRedirect('/');
});

it('redirects to login when not authenticated (admin)', function ($url): void {
    $this->get($url)->assertRedirect(route('login'));
})->with('admin_routes');

it('redirects to login when not authenticated (mailmerge)', function ($url): void {
    $this->get($url)->assertRedirect(route('login'));
})->with('mailmerge_routes');
