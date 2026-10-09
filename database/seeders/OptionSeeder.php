<?php

namespace Database\Seeders;

use App\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            ['name' => 'first_run', 'value' => '1'],
        ];

        foreach ($options as $option) {
            if ($option['name'] === 'first_run' && Option::where('name', 'first_run')->exists()) {
                continue; // Skip creating the option if it already exists
            }
            Option::create($option);
        }
    }
}
