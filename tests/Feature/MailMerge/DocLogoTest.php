<?php

use App\Models\MailMerge\DocLogo;
use App\Option;
use App\Role;
use App\User;
use Database\Seeders\OptionSeeder;

use function Pest\Faker\fake;

beforeEach(function () {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('can access the mailmerge logo panel as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.index'))->assertOk();
});

it('cannot access the mailmerge logo panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.index'))->assertForbidden();
});

it('can access the mailmerge logo panel as user with role DocLogoRead, DocLogoWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.index'))->assertOk();
});

it('can access a logo as admin', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $this->actingAs($admin)->get(route('apps.mailmerge.doclogo.show', $logo))->assertOk();
});

it('cannot access a logo as user', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.show', $logo))->assertForbidden();
});

it('can access a logo as user with role DocLogoRead, DocLogoWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.show', $logo))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.show', $logo))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.show', $logo))->assertOk();
});

it('can create a logo as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertOk();

    $logo_data = [
        'title' => fake()->sentence(),
        'text' => str_replace('. ', "\n", fake()->text()),
        'image' => fake()->word().'.jpg',
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.doclogo.store', $logo_data))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo saved!');

    $this->assertDatabaseHas('doc_logos', $logo_data);
});

it('cannot create a logo as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.doclogo.store', [
        'title' => fake()->sentence(),
        'text' => str_replace('. ', "\n", fake()->text()),
        'image' => fake()->word().'.jpg',
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('doc_logos', 0);
});

it('cannot create a logo as user with role DocLogoRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.doclogo.store', [
        'title' => fake()->sentence(),
        'text' => str_replace('. ', "\n", fake()->text()),
        'image' => fake()->word().'.jpg',
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('doc_logos', 0);
});

it('can create a logo as user with role DocLogoWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertOk();

    $logo_data = [
        'title' => fake()->sentence(),
        'text' => str_replace('. ', "\n", fake()->text()),
        'image' => fake()->word().'.jpg',
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.doclogo.store', $logo_data))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo saved!');

    $this->assertDatabaseHas('doc_logos', $logo_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertOk();

    $logo_data = [
        'title' => fake()->sentence(),
        'text' => str_replace('. ', "\n", fake()->text()),
        'image' => fake()->word().'.jpg',
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.doclogo.store', $logo_data))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo saved!');

    $this->assertDatabaseHas('doc_logos', $logo_data);
});

it('cannot create a logo as admin', function ($title, $text, $image, $active, $errors) {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.create'))->assertOk();

    $logo_data = [
        'title' => $title,
        'text' => $text,
        'image' => $image,
        'active' => $active,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.doclogo.store', $logo_data))
        ->assertRedirect(route('apps.mailmerge.doclogo.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('doc_logos', $logo_data);
})->with('invalid_logo_data');

it('can update a logo as admin', function () {
    $user = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo);
    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $logo_data = [
        'title' => 'This is an updated title',
        'text' => 'This is an updated text',
        'image' => 'update.jpg',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo updated!');

    $this->assertDatabaseHas('doc_logos', $logo_data);
});

it('cannot update a logo as user', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($logo);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertForbidden();

    $logo_data = [
        'title' => 'This is an updated title',
        'text' => 'This is an updated text',
        'image' => 'update.jpg',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('doc_logos', $logo_data);
});

it('cannot update a logo as user with role DocLogoRead', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($logo);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertForbidden();

    $logo_data = [
        'title' => 'This is an updated title',
        'text' => 'This is an updated text',
        'image' => 'update.jpg',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('doc_logos', $logo_data);
});

it('can update a logo as user with role DocLogoWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($logo);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoWrite']));

    // User cannot update a logo created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertForbidden();

    $logo_data = [
        'title' => 'This is an updated title',
        'text' => 'This is an updated text',
        'image' => 'update.jpg',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('doc_logos', $logo_data);

    // ...but he can update his own logos
    $logo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo);
    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertOk();

    $logo_data = [
        'title' => 'This is an updated title',
        'text' => 'This is an updated text',
        'image' => 'update.jpg',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo updated!');

    $this->assertDatabaseHas('doc_logos', $logo_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.doclogo.edit', $logo))->assertOk();

    $logo_data = [
        'title' => 'This is an updated title2',
        'text' => 'This is an updated text2',
        'image' => 'update2.jpg',
        'active' => true,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.doclogo.update', $logo), $logo_data)
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo updated!');

    $this->assertDatabaseHas('doc_logos', $logo_data);
});

it('can delete a logo as admin', function () {
    $user = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.doclogo.destroy', $logo))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo deleted!');

    $this->assertDatabaseCount('doc_logos', 0);
});

it('cannot delete a logo as user', function () {
    $user = User::factory()->user()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo);

    $this->actingAs($user)->delete(route('apps.mailmerge.doclogo.destroy', $logo))->assertForbidden();

    $this->assertModelExists($logo);
});

it('cannot delete a logo as user with role DocLogoRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoRead']));
    $logo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo);

    $this->actingAs($user)->delete(route('apps.mailmerge.doclogo.destroy', $logo))->assertForbidden();

    $this->assertModelExists($logo);
});

it('can delete a logo as user with role DocLogoWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $logo = DocLogo::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($logo);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'DocLogoWrite']));

    // User cannot delete a logo created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.doclogo.destroy', $logo))->assertForbidden();

    $this->assertModelExists($logo);

    // ...but he can delete his own logos
    $logo2 = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($logo2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.doclogo.destroy', $logo2))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo deleted!');

    $this->assertDatabaseCount('doc_logos', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.doclogo.destroy', $logo))
        ->assertRedirect(route('apps.mailmerge.doclogo.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Logo deleted!');

    $this->assertDatabaseCount('doc_logos', 0);
});
