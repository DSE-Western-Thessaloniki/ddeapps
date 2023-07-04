<?php

use App\Models\MailMerge\ExactCopy;
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

it('can access the mailmerge exact copy panel as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.index'))->assertOk();
});

it('cannot access the mailmerge exact copy panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.index'))->assertForbidden();
});

it('can access the mailmerge exact copy panel as user with role ExactCopyRead, ExactCopyWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.index'))->assertOk();
});

it('can access an exact copy as admin', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $this->actingAs($admin)->get(route('apps.mailmerge.exactcopy.show', $exactcopy))->assertOk();
});

it('cannot access an exact copy as user', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.show', $exactcopy))->assertForbidden();
});

it('can access an exact copy as user with role ExactCopyRead, ExactCopyWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.show', $exactcopy))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.show', $exactcopy))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.show', $exactcopy))->assertOk();
});

it('can create an exact copy as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertOk();

    $name = fake()->name().' '.fake()->lastName();
    $exactcopy_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', fake()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.exactcopy.store', $exactcopy_data))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο αποθηκεύτηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);
});

it('cannot create an exact copy as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertForbidden();

    $name = fake()->name().' '.fake()->lastName();
    $this->actingAs($user)->post(route('apps.mailmerge.exactcopy.store', [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', fake()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('exact_copies', 0);
});

it('cannot create an exact copy as user with role ExactCopyRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertForbidden();

    $name = fake()->name().' '.fake()->lastName();
    $this->actingAs($user)->post(route('apps.mailmerge.exactcopy.store', [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', fake()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('exact_copies', 0);
});

it('can create an exact copy as user with role ExactCopyWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertOk();

    $name = fake()->name().' '.fake()->lastName();
    $exactcopy_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', fake()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.exactcopy.store', $exactcopy_data))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο αποθηκεύτηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertOk();

    $name = fake()->name().' '.fake()->lastName();
    $exactcopy_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', fake()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.exactcopy.store', $exactcopy_data))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο αποθηκεύτηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);
});

it('cannot create an exact copy as admin', function ($title, $text, $active, $errors) {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.create'))->assertOk();

    $exactcopy_data = [
        'title' => $title,
        'text' => $text,
        'active' => $active,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.exactcopy.store', $exactcopy_data))
        ->assertRedirect(route('apps.mailmerge.exactcopy.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('exact_copies', $exactcopy_data);
})->with('invalid_exactcopy_data');

it('can update an exact copy as admin', function () {
    $user = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $exactcopy_data = [
        'title' => "This is an updated title",
        'text' => "Updated text",
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο ενημερώθηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);
});

it('cannot update an exact copy as user', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($exactcopy);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertForbidden();

    $exactcopy_data = [
        'title' => "This is an updated title",
        'text' => "Updated text",
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('exact_copies', $exactcopy_data);
});

it('cannot update an exactcopy as user with role ExactCopyRead', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($exactcopy);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertForbidden();

    $exactcopy_data = [
        'title' => "This is an updated title",
        'text' => "Updated text",
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('exact_copies', $exactcopy_data);
});

it('can update an exact copy as user with role ExactCopyWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($exactcopy);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyWrite']));

    // User cannot update an exactcopy created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertForbidden();

    $exactcopy_data = [
        'title' => "This is an updated title",
        'text' => "Updated text",
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('exact_copies', $exactcopy_data);

    // ...but he can update his own exact copies
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertOk();

    $exactcopy_data = [
        'title' => "This is an updated title",
        'text' => "Updated text",
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο ενημερώθηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertOk();

    $exactcopy_data = [
        'title' => "This is an updated title2",
        'text' => "Updated text2",
        'active' => true,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο ενημερώθηκε!');

    $this->assertDatabaseHas('exact_copies', $exactcopy_data);
});

it('cannot update an exact copy as admin', function ($title, $text, $active, $errors) {
    $user = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);
    $this->actingAs($user)->get(route('apps.mailmerge.exactcopy.edit', $exactcopy))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $exactcopy_data = [
        'title' => $title,
        'text' => $text,
        'active' => $active,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.exactcopy.update', $exactcopy), $exactcopy_data)
        ->assertRedirect(route('apps.mailmerge.exactcopy.edit', $exactcopy));
    // dd($response);
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('exact_copies', $exactcopy_data);
})->with('invalid_exactcopy_data');

it('can delete an exact copy as admin', function () {
    $user = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο διαγράφηκε!');

    $this->assertDatabaseCount('exact_copies', 0);
});

it('cannot delete an exact copy as user', function () {
    $user = User::factory()->user()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);

    $this->actingAs($user)->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy))->assertForbidden();

    $this->assertModelExists($exactcopy);
});

it('cannot delete an exact copy as user with role ExactCopyRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyRead']));
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy);

    $this->actingAs($user)->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy))->assertForbidden();

    $this->assertModelExists($exactcopy);
});

it('can delete an exactcopy as user with role ExactCopyWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $exactcopy = ExactCopy::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($exactcopy);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'ExactCopyWrite']));

    // User cannot delete an exactcopy created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy))->assertForbidden();

    $this->assertModelExists($exactcopy);

    // ...but he can delete his own exact copies
    $exactcopy2 = ExactCopy::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($exactcopy2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy2))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο διαγράφηκε!');

    $this->assertDatabaseCount('exact_copies', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.exactcopy.destroy', $exactcopy))
        ->assertRedirect(route('apps.mailmerge.exactcopy.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Το ακριβές αντίγραφο διαγράφηκε!');

    $this->assertDatabaseCount('exact_copies', 0);
});
