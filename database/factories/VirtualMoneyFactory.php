<?php

namespace Database\Factories;

use App\Models\VirtualMoney;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VirtualMoneyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VirtualMoney::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(1,2);
        $id = $this->faker->randomElement(User::all()->pluck('id')->toArray());
        if ($type == 1) {
            $id = $this->faker->randomElement(Company::all()->pluck('id')->toArray());
        }

        return [
            'type' => $type,
            'amount' => 0,
            'reference_id' => $id,
        ];
    }
}
