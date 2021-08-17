<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\QuoteDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuoteDetail::class;

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
            'quote_id' => $this->faker->randomElement(Quote::all()->pluck('id')->toArray()),
            'service_id' => $this->faker->randomNumber(1),
            'quantity' => $this->faker->randomNumber(2),
            'excl_tax_price' => $prixHT,
            'tax_id' => $this->faker->randomNumber(1),
            'tax_percent' => $taxe,
            'incl_tax_price' => $prixTTC,
        ];
    }
}
