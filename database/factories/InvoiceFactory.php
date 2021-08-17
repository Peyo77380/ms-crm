<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $last = Invoice::orderBy('number', 'desc')->first();
        $number = 1;
        if ($last) {
            $number = intval($last['number']) + 1;
        }
        return [
            'company_id' => $this->faker->randomElement(Company::all()->pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'number' => $number,
            'date' => $this->faker->date(),
            'excl_tax_total' => 0,
            'taxes_total' => 0,
            'incl_tax_total' => 0,
            'already_paid' => 0,
            'status_id' => $this->faker->optional()->randomNumber(2),
        ];
    }
}
