<?php

namespace Database\Factories;

use App\Models\CompanyHasUser;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyHasUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyHasUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->faker->randomElement(Company::all()->pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'role' => $this->faker->randomDigit(),
        ];
    }
}
