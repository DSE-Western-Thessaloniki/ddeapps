<?php

use App\Models\MailMerge\DocLogo;
use App\Models\MailMerge\Editor;
use App\Models\MailMerge\ExactCopy;
use App\Models\MailMerge\MailMerge;
use App\Models\MailMerge\Recipient;
use App\Models\MailMerge\Signature;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\DuskTestCase;
use Tests\TestCase;

use function Pest\Faker\fake;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, LazilyRefreshDatabase::class)->in('Feature');
uses(DuskTestCase::class, DatabaseMigrations::class)->in('Browser');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', fn() => $this->toBe(1));

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function test_create_mailmerge_for_user(User $user): MailMerge
{
    $mailmerge = MailMerge::factory()
        ->for(
            DocLogo::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'logo'
        )
        ->for(
            ExactCopy::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'exactCopy'
        )
        ->for(
            Signature::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'signature'
        )
        ->for(
            Editor::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'editor'
        )
        ->create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

    return $mailmerge;
}

function test_prepare_mailmerge_for_user(User $user): array
{
    $doclogo = DocLogo::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $editor = Editor::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $exactCopy = ExactCopy::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $signature = Signature::factory()->create([
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    return [$doclogo, $editor, $exactCopy, $signature];
}

function test_create_mailmerge_with_data_for_user(User $user): MailMerge
{
    $recipients = Recipient::factory()
        ->count(10)
        ->create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

    $fields = ['ΑΜ', 'ΟΝΟΜΑ', 'ΕΠΩΝΥΜΟ', 'ΚΛΑΔΟΣ', 'ΑΦ', 'ΠΑΡΑΛΗΠΤΗΣ'];
    $field_count = fake()->randomDigitNotNull();
    for ($i = 0; $i < $field_count; $i++) {
        array_push($fields, fake()->word());
    }
    $field_count++;
    $xlsxdata_header = json_encode($fields);

    $data = [];
    foreach ($recipients as $recipient) {
        $row = [];
        foreach ($fields as $column) {
            if ($column === 'ΠΑΡΑΛΗΠΤΗΣ') {
                $row[$column] = $recipient->name;
            } elseif ($column === 'ΕΠΩΝΥΜΟ') {
                $row[$column] = fake()->lastName();
            } elseif ($column === 'ΟΝΟΜΑ') {
                $row[$column] = fake()->name();
            } elseif ($column === 'ΚΛΑΔΟΣ') {
                $row[$column] = 'ΠΕ'.fake()->randomNumber(2);
            } elseif ($column === 'ΑΜ') {
                $row[$column] = fake()->randomNumber();
            } else {
                $row[$column] = fake()->word();
            }
        }
        array_push($data, $row);
    }
    $xlsxdata = json_encode($data);

    // TODO: this should work with multiple recipients
    $mergefields = '["ΠΑΡΑΛΗΠΤΗΣ"]';

    $mailmerge = MailMerge::factory()
        ->for(
            DocLogo::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'image' => 'logo.png',
            ]),
            'logo'
        )
        ->for(
            ExactCopy::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'exactCopy'
        )
        ->for(
            Signature::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'signature'
        )
        ->for(
            Editor::factory()->state([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]),
            'editor'
        )
        ->create([
            'xlsxdata' => $xlsxdata,
            'mergefields' => $mergefields,
            'xlsxdata_header' => $xlsxdata_header,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

    return $mailmerge;
}
