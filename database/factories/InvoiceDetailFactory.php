<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // calcul du prix avec taxe
        $prixHT = $this->faker->randomFloat(2, 0, 50);
        $taxe = $this->faker->randomFloat(2, 0, 25);
        $prixTTC = $prixHT*(1+$taxe/100);

        return [
            'invoice_id' => $this->faker->randomElement(Invoice::all()->pluck('id')->toArray()),
            'service_id' => $this->faker->randomNumber(1),
            'quantity' => $this->faker->randomNumber(2),
            'excl_tax_price' => $prixHT,
            'tax_id' => $this->faker->randomNumber(2),
            'tax_percent' => $taxe,
            'incl_tax_price' => $prixTTC,
        ];
    }
}
