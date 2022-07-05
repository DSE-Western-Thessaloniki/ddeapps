<?php

use App\Models\MailMerge\Recipient;
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

it('can access the mailmerge recipient panel as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.index'))->assertOk();
});

it('cannot access the mailmerge recipient panel as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.index'))->assertForbidden();
});

it('can access the mailmerge recipient panel as user with role RecipientRead, RecipientWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.index'))->assertOk();
});

it('can access an recipient as admin', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.recipient.show', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
});

it('cannot access an recipient as user', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.show', $recipient))->assertForbidden();
});

it('can access an recipient as user with role RecipientRead, RecipientWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.recipient.show', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.recipient.show', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.recipient.show', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
});

it('can create an recipient as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertOk();

    $recipient_data = [
        'name' => faker()->firstName().' '.faker()->lastName(),
        'code' => faker()->numerify('#######'),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.store', $recipient_data))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Recipient created!');

    $this->assertDatabaseHas('recipients', $recipient_data);
});

it('cannot create an recipient as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.recipient.store', [
        'name' => faker()->firstName().' '.faker()->lastName(),
        'code' => faker()->numerify('#######'),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('recipients', 0);
});

it('cannot create an recipient as user with role RecipientRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertForbidden();

    $this->actingAs($user)->post(route('apps.mailmerge.recipient.store', [
        'name' => faker()->firstName().' '.faker()->lastName(),
        'code' => faker()->numerify('#######'),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('recipients', 0);
});

it('can create an recipient as user with role RecipientWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertOk();

    $recipient_data = [
        'name' => faker()->firstName().' '.faker()->lastName(),
        'code' => faker()->numerify('#######'),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.store', $recipient_data))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Recipient created!');

    $this->assertDatabaseHas('recipients', $recipient_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertOk();

    $recipient_data = [
        'name' => faker()->firstName().' '.faker()->lastName(),
        'code' => faker()->numerify('#######'),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.store', $recipient_data))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Recipient created!');

    $this->assertDatabaseHas('recipients', $recipient_data);
});

it('cannot create an recipient as admin', function ($name, $code, $errors) {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.create'))->assertOk();

    $recipient_data = [
        'name' => $name,
        'code' => $code,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.store', $recipient_data))
        ->assertRedirect(route('apps.mailmerge.recipient.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('recipients', $recipient_data);
})->with('invalid_recipient_data');

it('can update an recipient as admin', function () {
    $user = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $recipient_data = [
        'name' => 'Updated name',
        'code' => '1234567',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του παραλήπτη ενημερώθηκαν!');

    $this->assertDatabaseHas('recipients', $recipient_data);
});

it('cannot update an recipient as user', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($recipient);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertForbidden();

    $recipient_data = [
        'name' => 'Updated name',
        'code' => '1234567',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('recipients', $recipient_data);
});

it('cannot update an recipient as user with role RecipientRead', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($recipient);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertForbidden();

    $recipient_data = [
        'name' => 'Updated name',
        'code' => '1234567',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('recipients', $recipient_data);
});

it('can update an recipient as user with role RecipientWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($recipient);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    // User cannot update an recipient created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertForbidden();

    $recipient_data = [
        'name' => 'Updated name',
        'code' => '1234567',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('recipients', $recipient_data);

    // ...but he can update his own recipients
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertOk();

    $recipient_data = [
        'name' => 'Updated name',
        'code' => '1234567',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του παραλήπτη ενημερώθηκαν!');

    $this->assertDatabaseHas('recipients', $recipient_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertOk();

    $recipient_data = [
        'name' => 'Updated name2',
        'code' => '1234567--',
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Τα στοιχεία του παραλήπτη ενημερώθηκαν!');

    $this->assertDatabaseHas('recipients', $recipient_data);
});

it('cannot update an recipient as admin', function ($name, $code, $errors) {
    $user = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);
    $this->actingAs($user)->get(route('apps.mailmerge.recipient.edit', $recipient))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    $recipient_data = [
        'name' => $name,
        'code' => $code,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $recipient_data)
        ->assertRedirect(route('apps.mailmerge.recipient.edit', $recipient));
    // dd($response);
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('recipients', $recipient_data);
})->with('invalid_recipient_data');

it('can delete an recipient as admin', function () {
    $user = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.recipient.destroy', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο παραλήπτης διαγράφηκε!');

    $this->assertDatabaseCount('recipients', 0);
});

it('cannot delete an recipient as user', function () {
    $user = User::factory()->user()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);

    $this->actingAs($user)->delete(route('apps.mailmerge.recipient.destroy', $recipient))->assertForbidden();

    $this->assertModelExists($recipient);
});

it('cannot delete an recipient as user with role RecipientRead', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));
    $recipient = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient);

    $this->actingAs($user)->delete(route('apps.mailmerge.recipient.destroy', $recipient))->assertForbidden();

    $this->assertModelExists($recipient);
});

it('can delete an recipient as user with role RecipientWrite or MailMergeAdmin', function () {
    $admin = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        "created_by" => $admin->id,
        "updated_by" => $admin->id,
    ]);
    $this->assertModelExists($recipient);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    // User cannot delete an recipient created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.recipient.destroy', $recipient))->assertForbidden();

    $this->assertModelExists($recipient);

    // ...but he can delete his own recipients
    $recipient2 = Recipient::factory()->create([
        "created_by" => $user->id,
        "updated_by" => $user->id,
    ]);
    $this->assertModelExists($recipient2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.recipient.destroy', $recipient2))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο παραλήπτης διαγράφηκε!');

    $this->assertDatabaseCount('recipients', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.recipient.destroy', $recipient))
        ->assertRedirect(route('apps.mailmerge.recipient.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Ο παραλήπτης διαγράφηκε!');

    $this->assertDatabaseCount('recipients', 0);
});

it('can access the mailmerge recipient list as admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.list'))->assertOk();
});

it('cannot access the mailmerge recipient list as user', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.list'))->assertForbidden();
});

it('can access the mailmerge recipient list as user with role RecipientRead, RecipientWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.list'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.list'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.recipient.list'))->assertOk();
});

it('can store many recipients as admin', function () {
    $user = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $many_recipients_data = [
        'many' => [
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertOk();

    $this->assertDatabaseCount('recipients', 4);
});

it('cannot store many recipients as user with and without RecipientRead', function () {
    $user = User::factory()->user()->create();

    $recipient = Recipient::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $many_recipients_data = [
        'many' => [
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertForbidden();

    $this->assertDatabaseCount('recipients', 1);

    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientRead']));

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertForbidden();

    $this->assertDatabaseCount('recipients', 1);
});

it('can store many recipients as user with role RecipientWrite or MailMergeAdmin', function () {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'RecipientWrite']));

    $recipient = Recipient::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $many_recipients_data = [
        'many' => [
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertOk();

    $this->assertDatabaseCount('recipients', 4);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(["name" =>'MailMergeAdmin']));

    $many_recipients_data = [
        'many' => [
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertOk();

    $this->assertDatabaseCount('recipients', 7);
});

it('can delete linked recipients as admin', function () {
    $user = User::factory()->admin()->create();
    $recipient = Recipient::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $many_recipients_data = [
        'many' => [
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'name' => faker()->firstName().' '.faker()->lastName(),
                'code' => faker()->numerify('#######'),
                'link' => $recipient->name,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('apps.mailmerge.recipient.storeMany'), $many_recipients_data)
        ->assertOk();

    $updated_recipient = $recipient->toArray();
    $updated_recipient['del_aliases'] = "[\"l".Recipient::all()->last()->id."\"]";

    $this->actingAs($user)
        ->put(route('apps.mailmerge.recipient.update', $recipient), $updated_recipient)
        ->assertRedirect(route('apps.mailmerge.recipient.index'));

    $this->assertDatabaseCount('recipients', 3);
});
