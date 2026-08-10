<?php

namespace Database\Factories\MailMerge;

use App\Models\MailMerge\ExactCopy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExactCopyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExactCopy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name().' '.$this->faker->lastName();

        return [
            'title' => $name,
            'text' => $name."\n\n".implode(' ', $this->faker->words(2)),
            'active' => true,
            'updated_by' => 1,
            'created_by' => 1,
        ];
    }
}
