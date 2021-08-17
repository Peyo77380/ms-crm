<?php

namespace Database\Factories;

use App\Models\VirtualMoney;
use App\Models\VirtualMoneyExchange;
use Illuminate\Database\Eloquent\Factories\Factory;

class VirtualMoneyExchangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VirtualMoneyExchange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'virtual_money_id' => $this->faker->randomElement(VirtualMoney::all()->pluck('id')->toArray()),
            'type' => $this->faker->randomNumber(1),
            'amount' => $this->faker->randomFloat(2, 0, 50),
            'info' => $this->faker->optional()->sentence()
        ];
    }
}
