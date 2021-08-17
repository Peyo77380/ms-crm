<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicePaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoicePayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_id' => $this->faker->randomElement(Invoice::all()->pluck('id')->toArray()),
            'amount' => $this->faker->randomFloat(2, 1, 50),
            'payment_code' => $this->faker->randomNumber(2),
            'info' => $this->faker->optional()->sentence(),
        ];
    }
}
