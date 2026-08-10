<?php

use App\Models\MailMerge\MailMerge;
use App\Option;
use App\Role;
use App\User;
use Database\Seeders\OptionSeeder;

use function Pest\Faker\fake;

beforeEach(function (): void {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('can access the mail merge panel as admin', function (): void {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.index'))->assertOk();
});

it('cannot access the mail merge panel as user', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.index'))->assertForbidden();
});

it('can access the mail merge panel as user with role MailMergeRead, MailMergeWrite or MailMergeAdmin', function (): void {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.index'))->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.index'))->assertOk();
});

it('can access a mail merge as admin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();
});

it('cannot access a mail merge as user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.show', $mailMerge))->assertForbidden();
});

it('can access a mail merge as user with role MailMergeRead, MailMergeWrite or MailMergeAdmin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();
});

it('can create a mail merge as admin', function (): void {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.store', $mailMerge_data))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση αποθηκεύτηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);
});

it('cannot create a mail merge as user', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertForbidden();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $this->actingAs($user)->post(route('apps.mailmerge.store', [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('mail_merges', 0);
});

it('cannot create a mail merge as user with role MailMergeRead', function (): void {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertForbidden();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $this->actingAs($user)->post(route('apps.mailmerge.store', [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]))->assertForbidden();

    $this->assertDatabaseCount('mail_merges', 0);
});

it('can create a mail merge as user with role MailMergeWrite or MailMergeAdmin', function (): void {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.store', $mailMerge_data))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση αποθηκεύτηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.store', $mailMerge_data))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση αποθηκεύτηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);
});

it('cannot create a mail merge as admin', function ($protocol_num, $date, $subject, $text, $ada, $files_for_teachers, $errors): void {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.create'))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => $protocol_num,
        'date' => $date,
        'subject' => $subject,
        'text' => $text,
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => $ada,
        'files_for_teachers' => $files_for_teachers,
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->post(route('apps.mailmerge.store', $mailMerge_data))
        ->assertRedirect(route('apps.mailmerge.create'));
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('mail_merges', $mailMerge_data);
})->with('invalid_mail_merge_data');

it('can update a mail merge as admin', function (): void {
    $user = User::factory()->admin()->create();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση ενημερώθηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);
});

it('cannot update a mail merge as user', function (): void {
    $admin = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($admin);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($mailMerge);

    $user = User::factory()->user()->create();
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertForbidden();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('mail_merges', $mailMerge_data);
});

it('cannot update a mail merge as user with role MailMergeRead', function (): void {
    $admin = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($admin);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($mailMerge);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertForbidden();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('mail_merges', $mailMerge_data);
});

it('can update a mail merge as user with role MailMergeWrite or MailMergeAdmin', function (): void {
    $admin = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($admin);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($mailMerge);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    // User cannot update a mail merge created by someone else
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertForbidden();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertForbidden();

    $this->assertDatabaseMissing('mail_merges', $mailMerge_data);

    // ...but he can update his own mail_merges
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση ενημερώθηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertOk();

    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση ενημερώθηκε!');

    $this->assertDatabaseHas('mail_merges', $mailMerge_data);
});

it('cannot update a mail merge as admin', function ($protocol_num, $date, $subject, $text, $ada, $files_for_teachers, $errors): void {
    $user = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'updated_by' => $user->id,
        'created_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);
    $this->actingAs($user)->get(route('apps.mailmerge.edit', $mailMerge))->assertOk();

    // Create a new admin to check that updated_by is updated correctly
    $user = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge_data = [
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => $protocol_num,
        'date' => $date,
        'subject' => $subject,
        'text' => $text,
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => $ada,
        'files_for_teachers' => $files_for_teachers,
        'updated_by' => $user->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('apps.mailmerge.update', $mailMerge), $mailMerge_data)
        ->assertRedirect(route('apps.mailmerge.edit', $mailMerge));
    // dd($response);
    expect($response->getSession()->only(['errors'])['errors'])->not->toBeEmpty();
    expect(array_diff($response->getSession()->only(['errors'])['errors']->getBag('default')->keys(), $errors))->toBeEmpty();

    $this->assertDatabaseMissing('mail_merges', $mailMerge_data);
})->with('invalid_mail_merge_data');

it('can delete a mail merge as admin', function (): void {
    $user = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.destroy', $mailMerge))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση διαγράφηκε!');

    $this->assertDatabaseCount('mail_merges', 0);
});

it('cannot delete a mail merge as user', function (): void {
    $user = User::factory()->user()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);

    $this->actingAs($user)->delete(route('apps.mailmerge.destroy', $mailMerge))->assertForbidden();

    $this->assertModelExists($mailMerge);
});

it('cannot delete a mail merge as user with role MailMergeRead', function (): void {
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge);

    $this->actingAs($user)->delete(route('apps.mailmerge.destroy', $mailMerge))->assertForbidden();

    $this->assertModelExists($mailMerge);
});

it('can delete a mail merge as user with role MailMergeWrite or MailMergeAdmin', function (): void {
    $admin = User::factory()->admin()->create();
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($admin);
    $mailMerge = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);
    $this->assertModelExists($mailMerge);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    // User cannot delete a mail merge created by someone else
    $this->actingAs($user)->delete(route('apps.mailmerge.destroy', $mailMerge))->assertForbidden();

    $this->assertModelExists($mailMerge);

    // ...but he can delete his own mail_merges
    [$doclogo, $editor, $exact_copy, $signature] = test_prepare_mailmerge_for_user($user);
    $mailMerge2 = MailMerge::factory()->create([
        'logo_id' => $doclogo->id,
        'editor_id' => $editor->id,
        'exact_copy_id' => $exact_copy->id,
        'signature_id' => $signature->id,
        'protocol_num' => strval(fake()->randomNumber()),
        'date' => fake()->date(),
        'subject' => fake()->sentence(),
        'text' => fake()->paragraph(),
        'xlsxdata' => '[]',
        'mergefields' => '[]',
        'xlsxdata_header' => '[]',
        'ada' => fake()->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
        'files_for_teachers' => fake()->boolean(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $this->assertModelExists($mailMerge2);
    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.destroy', $mailMerge2))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση διαγράφηκε!');

    $this->assertDatabaseCount('mail_merges', 1);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $response = $this->actingAs($user)
        ->delete(route('apps.mailmerge.destroy', $mailMerge))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση διαγράφηκε!');

    $this->assertDatabaseCount('mail_merges', 0);
});

it('can copy a mail merge as admin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);

    $response = $this->actingAs($admin)
        ->get(route('apps.mailmerge.copy', $mailMerge))
        ->assertRedirect(route('apps.mailmerge.index'));
    expect($response->getSession()->only(['status'])['status'])->toBe('Η κοινοποίηση αντιγράφηκε!');

    $this->assertDatabaseCount('mail_merges', 2);
});

it('cannot copy a mail merge as user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get(route('apps.mailmerge.copy', $mailMerge))->assertForbidden();

    $this->assertDatabaseCount('mail_merges', 1);
});

it('cannot copy a mail merge as user with role MailMergeRead', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);
    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeRead']));

    $this->actingAs($user)->get(route('apps.mailmerge.copy', $mailMerge))->assertForbidden();

    $this->assertDatabaseCount('mail_merges', 1);
});

it('can copy a mail merge as user with role MailMergeWrite or MailMergeAdmin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailMerge = test_create_mailmerge_for_user($admin);

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeWrite']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();

    $user = User::factory()->user()->create();
    $user->roles()->attach(Role::factory()->create(['name' => 'MailMergeAdmin']));

    $this->actingAs($user)
        ->get(route('apps.mailmerge.show', $mailMerge))
        ->assertOk();
});
