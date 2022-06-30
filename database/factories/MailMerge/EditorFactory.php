<?php

namespace Database\Factories\MailMerge;

use App\Models\MailMerge\Editor;
use Illuminate\Database\Eloquent\Factories\Factory;

class EditorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Editor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'name' => $this->faker->firstName().' '.$this->faker->lastName(),
            'telephone' => $this->faker->numerify('##########'),
            'email' => $this->faker->email(),
            'updated_by' => 1,
            'created_by' => 1,
        ];
    }
}
