<?php

namespace Database\Factories\MailMerge;

use App\Models\MailMerge\MailMerge;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailMergeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MailMerge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'logo_id' => '',
            'exact_copy_id' => '',
            'signature_id' => '',
            'editor_id' => '',
            'protocol_num' => $this->faker->randomNumber(),
            'date' => $this->faker->date(),
            'subject' => $this->faker->sentence(),
            'text' => $this->faker->paragraph(),
            'xlsxdata' => '[]',
            'mergefields' => '[]',
            'xlsxdata_header' => '[]',
            'ada' => $this->faker->regexify('[A-Z0-9]{10}-[A-Z0-9]{3}'),
            'files_for_teachers' => $this->faker->boolean(),
            'updated_by' => 1,
            'created_by' => 1,
        ];
    }
}
