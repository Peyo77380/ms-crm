<?php

namespace Database\Factories;

use App\Models\LogAccounting;
use App\Models\Quote;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogAccountingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LogAccounting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(1,2);
        $id = $this->faker->randomElement(Quote::all()->pluck('id')->toArray());
        $query = Quote::find($id);
        if ($type == 1) {
            $id = $this->faker->randomElement(Invoice::all()->pluck('id')->toArray());
            $query = Invoice::find($id);
        }
        $number = $query['number'];

        return [
            'action' => $this->faker->randomNumber(1),
            'type' => $type,
            'reference_id' => $id,
            'reference_number' => $number,
            'author_id' => $this->faker->randomNumber(2),
            'info' => $this->faker->optional()->sentence(),
        ];
    }
}
