<?php

namespace Database\Seeders;

use App\Models\MailMerge\DocLogo;
use App\Models\MailMerge\Editor;
use App\Models\MailMerge\ExactCopy;
use App\Models\MailMerge\MailMerge;
use App\Models\MailMerge\Recipient;
use App\Models\MailMerge\Signature;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

use function Pest\Faker\fake;

class TestSeeder extends Seeder
{
    public function addMailMergeForUser(User $user)
    {
        $recipients = Recipient::all();
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

        // dd($recipient);
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
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::firstWhere('name', '=', 'Administrator');
        $mail_merge_admin_role = Role::firstWhere('name', '=', 'MailMergeAdmin');
        $user_role = Role::firstWhere('name', '=', 'User');

        $admin = User::factory()
            ->hasAttached($admin_role)
            ->create(
                ['username' => 'admin']
            );

        $mail_merge_admins = User::factory()
            ->count(10)
            ->hasAttached($mail_merge_admin_role)
            ->create();

        User::factory()
            ->count(10)
            ->hasAttached($user_role)
            ->create();

        Recipient::factory()
            ->count(10)
            ->create([
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

        foreach ($mail_merge_admins as $mail_merge_admin) {
            $this->addMailMergeForUser($mail_merge_admin);
        }
    }
}
