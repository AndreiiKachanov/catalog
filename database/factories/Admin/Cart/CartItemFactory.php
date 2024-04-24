<?php

namespace Database\Factories\Admin\Cart;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Item\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cnt' => $this->faker->numberBetween(1, 50), // Пример генерации случайного значения для cnt
            'item_id' => Item::inRandomOrder()->first()->id,
        ];
    }
}
