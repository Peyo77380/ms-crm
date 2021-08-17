<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $last = Quote::orderBy('number', 'desc')->first();
        $number = 1;
        if ($last) {
            $number = intval($last['number']) + 1;
        }

        return [
            'company_id' => $this->faker->randomElement(Company::all()->pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'number' => $number,
            'excl_tax_total' => 0,
            'taxes_total' => 0,
            'incl_tax_total' => 0,
            'date' => $this->faker->date(),
            'status_id' => $this->faker->randomNumber(2),
        ];
    }
}
