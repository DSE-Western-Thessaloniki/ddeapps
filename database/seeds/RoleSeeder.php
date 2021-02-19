<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Administrator'],
            ['name' => 'User'],
            ['name' => 'MailMergeAdmin'],
            ['name' => 'EditorRead'],
            ['name' => 'EditorWrite'],
            ['name' => 'DocLogoRead'],
            ['name' => 'DocLogoWrite'],
            ['name' => 'ExactCopyRead'],
            ['name' => 'ExactCopyWrite'],
            ['name' => 'SignatureRead'],
            ['name' => 'SignatureWrite'],
            ['name' => 'RecipientRead'],
            ['name' => 'RecipientWrite'],
            ['name' => 'MailMergeRead'],
            ['name' => 'MailMergeWrite'],
        ];

        foreach($roles as $role) {
            Role::updateOrCreate($role);
        }
    }
}
