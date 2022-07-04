<?php

use App\Models\MailMerge\Editor;
use App\Option;
use App\Role;
use App\User;
use Database\Seeders\OptionSeeder;

use function Pest\Faker\faker;

beforeEach(function () {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('can access the mailmerge editor panel as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.index'))->assertOk();
});

it('cannot access the mailmerge editor panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.index'))->assertForbidden();
});

it('can access the mailmerge editor panel as user with role EditorRead, EditorWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.index'))->assertOk();
});

it('can access an editor as admin', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $this->actingAs($admin)->get(route('apps.mailmerge.editor.show', $editor))->assertOk();
});

it('cannot access an editor as user', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.show', $editor))->assertForbidden();
});

it('can access an editor as user with role EditorRead, EditorWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.show', $editor))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.show', $editor))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.show', $editor))->assertOk();
});

it('can create an editor as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertOk();

    $editor_data = [
        'title' => faker()->sentence(),
        'address' => faker()->address(),
        'name' => faker()->firstName().' '.faker()->lastName(),
        'telephone' => faker()->numerify('##########'),
        'email' => faker()->email(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.editor.store', $editor_data))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη αποθηκεύτηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);
});

it('cannot create an editor as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.editor.store', [
        'title' => faker()->sentence(),
        'address' => faker()->address(),
        'name' => faker()->firstName().' '.faker()->lastName(),
        'telephone' => faker()->numerify('##########'),
        'email' => faker()->email(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('editors', 0);
});

it('cannot create an editor as user with role EditorRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.editor.store', [
        'title' => faker()->sentence(),
        'address' => faker()->address(),
        'name' => faker()->firstName().' '.faker()->lastName(),
        'telephone' => faker()->numerify('##########'),
        'email' => faker()->email(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('editors', 0);
});

it('can create an editor as user with role EditorWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertOk();

    $editor_data = [
        'title' => faker()->sentence(),
        'address' => faker()->address(),
        'name' => faker()->firstName().' '.faker()->lastName(),
        'telephone' => faker()->numerify('##########'),
        'email' => faker()->email(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.editor.store', $editor_data))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη αποθηκεύτηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertOk();

    $editor_data = [
        'title' => faker()->sentence(),
        'address' => faker()->address(),
        'name' => faker()->firstName().' '.faker()->lastName(),
        'telephone' => faker()->numerify('##########'),
        'email' => faker()->email(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.editor.store', $editor_data))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη αποθηκεύτηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);
});

it('cannot create an editor as admin', function ($title, $address, $name, $telephone, $email, $errors) {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.editor.create'))->assertOk();

    $editor_data = [
        'title' => $title,
        'address' => $address,
        'name' => $name,
        'telephone' => $telephone,
        'email' => $email,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.editor.store', $editor_data))
        ->assertRedirect(route('apps.mailmerge.editor.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('editors', $editor_data);
})->with('invalid_editor_data');

it('can update an editor as admin', function () {
    $user = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $editor_data = [
        'title' => "This is an updated title",
        'address' => "Updated address",
        'name' => "New Name",
        'telephone' => '1234567890',
        'email' => 'new@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη ενημερώθηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);
});

it('cannot update an editor as user', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($editor);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertForbidden();

    $editor_data = [
        'title' => "This is an updated title",
        'address' => "Updated address",
        'name' => "New Name",
        'telephone' => '1234567890',
        'email' => 'new@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('editors', $editor_data);
});

it('cannot update an editor as user with role EditorRead', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($editor);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertForbidden();

    $editor_data = [
        'title' => "This is an updated title",
        'address' => "Updated address",
        'name' => "New Name",
        'telephone' => '1234567890',
        'email' => 'new@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('editors', $editor_data);
});

it('can update an editor as user with role EditorWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($editor);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorWrite']));

    // User cannot update an editor created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertForbidden();

    $editor_data = [
        'title' => "This is an updated title",
        'address' => "Updated address",
        'name' => "New Name",
        'telephone' => '1234567890',
        'email' => 'new@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('editors', $editor_data);

    // ...but he can update his own editors
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertOk();

    $editor_data = [
        'title' => "This is an updated title",
        'address' => "Updated address",
        'name' => "New Name",
        'telephone' => '1234567890',
        'email' => 'new@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη ενημερώθηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertOk();

    $editor_data = [
        'title' => "This is an updated title2",
        'address' => "Updated address2",
        'name' => "New Name2",
        'telephone' => '0123456789',
        'email' => 'new2@email.com',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του συντάκτη ενημερώθηκαν!');

    $this->assertDatabaseHas('editors', $editor_data);
});

it('cannot update an editor as admin', function ($title, $address, $name, $telephone, $email, $errors) {
    $user = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);
    $this->actingAs($user)->get(route('apps.mailmerge.editor.edit', $editor))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $editor_data = [
        'title' => $title,
        'address' => $address,
        'name' => $name,
        'telephone' => $telephone,
        'email' => $email,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.editor.update', $editor), $editor_data)
        ->assertRedirect(route('apps.mailmerge.editor.edit', $editor));
    // dd($response);
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('editors', $editor_data);
})->with('invalid_editor_data');

it('can delete an editor as admin', function () {
    $user = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.editor.destroy', $editor))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο συντάκτης διαγράφηκε!');

    $this->assertDatabaseCount('editors', 0);
});

it('cannot delete an editor as user', function () {
    $user = User::factory()->user()->create();
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);

    $this->actingAs($user)->delete(route('apps.mailmerge.editor.destroy', $editor))->assertForbidden();

    $this->assertModelExists($editor);
});

it('cannot delete an editor as user with role EditorRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorRead']));
    $editor = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor);

    $this->actingAs($user)->delete(route('apps.mailmerge.editor.destroy', $editor))->assertForbidden();

    $this->assertModelExists($editor);
});

it('can delete an editor as user with role EditorWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $editor = Editor::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($editor);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'EditorWrite']));

    // User cannot delete an editor created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.editor.destroy', $editor))->assertForbidden();

    $this->assertModelExists($editor);

    // ...but he can delete his own editors
    $editor2 = Editor::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($editor2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.editor.destroy', $editor2))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο συντάκτης διαγράφηκε!');

    $this->assertDatabaseCount('editors', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.editor.destroy', $editor))
        ->assertRedirect(route('apps.mailmerge.editor.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο συντάκτης διαγράφηκε!');

    $this->assertDatabaseCount('editors', 0);
});
