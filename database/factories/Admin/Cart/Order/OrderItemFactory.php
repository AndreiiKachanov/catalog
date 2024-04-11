<?php

namespace Database\Factories\Admin\Cart\Order;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Item\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Cart\Order\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cartItem = CartItem::inRandomOrder()->first();

        return [
            'item_id' => $cartItem->item->id,
            'cart_item_id' => $cartItem->id,
            'cnt' => $this->faker->randomDigitNotNull
        ];
    }
}
