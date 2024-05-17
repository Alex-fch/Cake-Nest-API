<?php

namespace Database\Factories;

use App\Models\CupCake;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CupCakeOrder>
 */
class CupCakeOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cupcake = CupCake::find($this->faker->randomElement(Cupcake::pluck('id')));

        return [
            'order_id' => Order::factory()->create()->id,
            'cupcake_id' => $cupcake->id,
            'price' => $cupcake->price,
            'quantity' => $this->faker->randomNumber(2),
        ];
    }
}
