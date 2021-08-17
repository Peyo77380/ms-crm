<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'siret' => $this->faker->optional()->randomNumber(),
            'vat_number' => $this->faker->optional()->randomNumber(),
            'vat_applicable' => rand(0,1),
            'type' => $this->faker->randomDigit(),
            'activity' => $this->faker->randomDigit(),
            'address' => $this->faker->streetAddress(),
            'zip' => $this->faker->randomNumber(5),
            'city' => $this->faker->city(),
            'social_network' => $this->faker->optional()->url(),
            'status_id' => $this->faker->optional()->randomNumber(2),
        ];
    }
}
