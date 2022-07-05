<?php

use App\Models\MailMerge\Signature;
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

it('can access the mailmerge signature panel as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.index'))->assertOk();
});

it('cannot access the mailmerge signature panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.index'))->assertForbidden();
});

it('can access the mailmerge signature panel as user with role SignatureRead, SignatureWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.index'))->assertOk();
});

it('can access a signature as admin', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.signature.show', $signature))
        ->assertOk();
});

it('cannot access a signature as user', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.show', $signature))->assertForbidden();
});

it('can access a signature as user with role SignatureRead, SignatureWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureRead']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.signature.show', $signature))
        ->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureWrite']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.signature.show', $signature))
        ->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.signature.show', $signature))
        ->assertOk();
});

it('can create a signature as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertOk();

    $name = faker()->name().' '.faker()->lastName();
    $signature_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', faker()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.signature.store', $signature_data))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον αποθηκεύτηκε!');

    $this->assertDatabaseHas('signatures', $signature_data);
});

it('cannot create a signature as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertForbidden();

    $name = faker()->name().' '.faker()->lastName();
    $this->actingAs($user)->post(route('apps.mailmerge.signature.store', [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', faker()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('signatures', 0);
});

it('cannot create a signature as user with role SignatureRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertForbidden();

    $name = faker()->name().' '.faker()->lastName();
    $this->actingAs($user)->post(route('apps.mailmerge.signature.store', [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', faker()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('signatures', 0);
});

it('can create a signature as user with role SignatureWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertOk();

    $name = faker()->name().' '.faker()->lastName();
    $signature_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', faker()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.signature.store', $signature_data))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον αποθηκεύτηκε!');

    $this->assertDatabaseHas('signatures', $signature_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertOk();

    $name = faker()->name().' '.faker()->lastName();
    $signature_data = [
        'title' => $name,
        'text' => $name."\n\n".implode(' ', faker()->words(2)),
        'active' => true,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.signature.store', $signature_data))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον αποθηκεύτηκε!');

    $this->assertDatabaseHas('signatures', $signature_data);
});

it('cannot create a signature as admin', function ($title, $text, $active, $errors) {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.signature.create'))->assertOk();

    $name = faker()->name().' '.faker()->lastName();
    $signature_data = [
        'title' => $title,
        'text' => $text,
        'active' => $active,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.signature.store', $signature_data))
        ->assertRedirect(route('apps.mailmerge.signature.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('signatures', $signature_data);
})->with('invalid_signature_data');

it('can update a signature as admin', function () {
    $user = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $signature_data = [
        'title' => 'Updated name',
        'text' => '1234567',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του υπογράφοντα αποθηκεύτηκαν!');

    $this->assertDatabaseHas('signatures', $signature_data);
});

it('cannot update a signature as user', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($signature);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertForbidden();

    $signature_data = [
        'title' => 'Updated title',
        'text' => '1234567',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('signatures', $signature_data);
});

it('cannot update a signature as user with role SignatureRead', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($signature);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertForbidden();

    $signature_data = [
        'title' => 'Updated title',
        'text' => '1234567',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('signatures', $signature_data);
});

it('can update a signature as user with role SignatureWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($signature);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureWrite']));

    // User cannot update a signature created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertForbidden();

    $signature_data = [
        'title' => 'Updated title',
        'text' => '1234567',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('signatures', $signature_data);

    // ...but he can update his own signatures
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertOk();

    $signature_data = [
        'title' => 'Updated title',
        'text' => '1234567',
        'active' => false,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του υπογράφοντα αποθηκεύτηκαν!');

    $this->assertDatabaseHas('signatures', $signature_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertOk();

    $signature_data = [
        'title' => 'Updated title2',
        'text' => '1234567--',
        'active' => true,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του υπογράφοντα αποθηκεύτηκαν!');

    $this->assertDatabaseHas('signatures', $signature_data);
});

it('cannot update a signature as admin', function ($title, $text, $active, $errors) {
    $user = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);
    $this->actingAs($user)->get(route('apps.mailmerge.signature.edit', $signature))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $signature_data = [
        'title' => $title,
        'text' => $text,
        'active' => $active,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.signature.update', $signature), $signature_data)
        ->assertRedirect(route('apps.mailmerge.signature.edit', $signature));
    // dd($response);
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('signatures', $signature_data);
})->with('invalid_signature_data');

it('can delete a signature as admin', function () {
    $user = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.signature.destroy', $signature))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον διαγράφηκε!');

    $this->assertDatabaseCount('signatures', 0);
});

it('cannot delete a signature as user', function () {
    $user = User::factory()->user()->create();
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);

    $this->actingAs($user)->delete(route('apps.mailmerge.signature.destroy', $signature))->assertForbidden();

    $this->assertModelExists($signature);
});

it('cannot delete a signature as user with role SignatureRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureRead']));
    $signature = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature);

    $this->actingAs($user)->delete(route('apps.mailmerge.signature.destroy', $signature))->assertForbidden();

    $this->assertModelExists($signature);
});

it('can delete a signature as user with role SignatureWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $signature = Signature::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($signature);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'SignatureWrite']));

    // User cannot delete a signature created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.signature.destroy', $signature))->assertForbidden();

    $this->assertModelExists($signature);

    // ...but he can delete his own signatures
    $signature2 = Signature::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($signature2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.signature.destroy', $signature2))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον διαγράφηκε!');

    $this->assertDatabaseCount('signatures', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.signature.destroy', $signature))
        ->assertRedirect(route('apps.mailmerge.signature.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο υπογράφον διαγράφηκε!');

    $this->assertDatabaseCount('signatures', 0);
});
