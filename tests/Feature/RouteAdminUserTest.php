<?php

use App\Option;
use App\User;
use Database\Seeders\OptionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('cannot access the users panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin/user')->assertForbidden();
});

it('can access the users panel as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/user')->assertOk();
});

it('cannot access another user\'s info as user', function () {
    $user = User::factory()->user()->create();
    $testUser = User::factory()->create();

    $this->actingAs($user)->get('/admin/user/'.$testUser->id)->assertForbidden();
});

it('can access another user\'s info as admin', function () {
    $admin = User::factory()->admin()->create();
    $testUser = User::factory()->create();

    $this->actingAs($admin)->get('/admin/user/'.$testUser->id)->assertOk();
});

it('cannot access it\'s own user info as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin/user/'.$user->id)->assertForbidden();
});

it('can access it\'s own user info as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/user/'.$admin->id)->assertOk();
});

it('cannot create another user as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin/user/create')->assertForbidden();
    $this->actingAs($user)->post('/admin/user', [
        'name' => 'Test User',
        'email' => 'test@test.com',
        'username' => 'testUser',
        'password' => 'mySecretPassword',
        'password_confirmation' => 'mySecretPassword',
    ])->assertForbidden();
});

it('can create another user as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/user/create')->assertOk();
    $response = $this->actingAs($admin)->post('/admin/user', [
        'name' => 'Test User',
        'email' => 'test@test.com',
        'username' => 'testUser',
        'password' => 'mySecretPassword',
        'password_confirmation' => 'mySecretPassword',
    ]);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User saved!');
});

it('cannot edit another user\'s info as user', function () {
    $user = User::factory()->user()->create();
    $testUser = User::factory()->create();

    $this->actingAs($user)->get('/admin/user/'.$testUser->id.'/edit')->assertForbidden();
});

it('can edit another user\'s info as admin', function () {
    $admin = User::factory()->admin()->create();
    $testUser = User::factory()->create();

    $this->actingAs($admin)->get('/admin/user/'.$testUser->id.'/edit')->assertOk();
});

it('cannot edit it\'s own user info as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin/user/'.$user->id.'/edit')->assertForbidden();
});

it('can edit it\'s own user info as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/user/'.$admin->id.'/edit')->assertOk();
});

it('cannot edit another user\'s password as user', function () {
    $user = User::factory()->user()->create();
    $testUser = User::factory()->create();

    $this->actingAs($user)->get('/admin/user/'.$testUser->id.'/password')->assertForbidden();
});

it('can edit another user\'s password as admin', function () {
    $admin = User::factory()->admin()->create();
    $testUser = User::factory()->create();

    $this->actingAs($admin)->get('/admin/user/'.$testUser->id.'/password')->assertOk();
});

it('can edit it\'s own password as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/password')->assertOk();
});

it('can edit it\'s own password as admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/user/'.$admin->id.'/password')->assertOk();
});

it('cannot delete another user as user', function () {
    $user = User::factory()->user()->create();
    $testUser = User::factory()->create();

    $this->actingAs($user)->delete('/admin/user/'.$testUser->id)->assertForbidden();
});

it('can delete another user as admin', function () {
    $admin = User::factory()->admin()->create();
    $testUser = User::factory()->create();

    $response = $this->actingAs($admin)->delete('/admin/user/'.$testUser->id);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User deleted!');
});

it('cannot delete it\'s own user as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->delete('/admin/user/'.$user->id)->assertForbidden();
});

it('can delete it\'s own user as admin', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->delete('/admin/user/'.$admin->id);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User deleted!');
});

it('cannot update another user\'s data as user', function () {
    $user = User::factory()->user()->create();
    $testUser = User::factory()->create();

    $this->actingAs($user)->put('/admin/user/'.$testUser->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
    ])->assertForbidden();
});

it('can update another user\'s data as admin', function () {
    $admin = User::factory()->admin()->create();
    $testUser = User::factory()->create();

    $response = $this->actingAs($admin)->put('/admin/user/'.$testUser->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
    ]);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User updated!');
});

it('cannot update it\'s own user data as user', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->put('/admin/user/'.$user->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
    ])->assertForbidden();
});

it('can update it\'s own user data data as admin', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->put('/admin/user/'.$admin->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
    ]);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User updated!');
});

it('can change a user\'s password as admin', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->user()->create();

    $response = $this->actingAs($admin)->post('/admin/user/'.$user->id.'/password', [
        'password' => 'test_password',
        'password_confirmation' => 'test_password',
    ]);
    $response->assertRedirect(route('admin.user.index'))
        ->assertSessionHas('status', 'Η αλλαγή του κωδικού ολοκληρώθηκε!');
});

it('cannot change a user\'s password as user', function () {
    $user = User::factory()->user()->create();
    $user2 = User::factory()->user()->create();

    $response = $this->actingAs($user2)->post('/admin/user/'.$user->id.'/password', [
        'password' => 'test_password',
        'password_confirmation' => 'test_password',
    ]);
    $response->assertForbidden();
});

it('can change it\'s own password as user', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->post('/password', [
        'cur_password' => 'password',
        'password' => 'test_password',
        'password_confirmation' => 'test_password',
    ]);
    $response->assertRedirect(route('home'))
        ->assertSessionHas('status', 'Η αλλαγή του κωδικού ολοκληρώθηκε!');
});

it('can change it\'s own password as admin', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/admin/user/'.$admin->id.'/password', [
        'password' => 'test_password',
        'password_confirmation' => 'test_password',
    ]);
    $response->assertRedirect(route('admin.user.index'))
        ->assertSessionHas('status', 'Η αλλαγή του κωδικού ολοκληρώθηκε!');
});

it('cannot update it\'s own role as user', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->put('/admin/user/'.$user->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
        'Administrator' => 1,
    ])->assertForbidden();
});

it('can update it\'s own role as admin', function () {
    $this->seed(RoleSeeder::class);
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->put('/admin/user/'.$admin->id, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testUser',
        'User' => 1,
    ]);
    $response->assertStatus(302);
    expect($response->getSession()->only(['status'])['status'])->toBe('User updated!');
    $this->assertEquals($admin->roles()->where('name', 'User')->count(), 1);
});

it('cannot login with deactivated account', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->user()->create();
    $user->active = 0;
    $user->save();

    $response = $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ]);
    $response->assertStatus(302)->assertRedirect(route('login'));
    expect($response->getSession()->only(['error'])['error'])->toBe('Ο λογαριασμός σας είναι απενεργοποιημένος.');
});
