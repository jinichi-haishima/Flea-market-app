<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'buyer_id' => User::factory(),
            'shipping_postal_code' => $this->faker->postcode,
            'shipping_address' => $this->faker->address,
            'shipping_building' => $this->faker->secondaryAddress,
            'payment' => $this->faker->creditCardNumber,
        ];
    }
}
