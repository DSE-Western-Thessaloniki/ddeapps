<?php

namespace Database\Factories\MailMerge;

use App\Models\MailMerge\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName().' '.$this->faker->lastName(),
            'code' => $this->faker->numerify('#######'),
            'link' => '',
            'updated_by' => 1,
            'created_by' => 1,
        ];
    }
}
