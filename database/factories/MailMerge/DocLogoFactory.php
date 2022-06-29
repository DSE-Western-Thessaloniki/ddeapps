<?php

namespace Database\Factories\MailMerge;

use App\Models\MailMerge\DocLogo;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocLogoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocLogo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'text' => str_replace('.', "\n", $this->faker->text()),
            'image' => $this->faker->word().'.jpg',
            'active' => true,
            'updated_by' => 1,
            'created_by' => 1,
        ];
    }
}
