<?php

namespace Database\Factories\Admin\Cart;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Item\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Cart\CartItem>
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
    //public function configure()
    //{
        //return $this->afterCreating(function (CartItem $cartItem) {
            // Создаем и связываем с CartItem экземпляр Item с использованием фабрики ItemFactory
            //$item = Item::factory()->create();
            //$cartItem->item()->associate($item)->save();

            //$order = Order::factory()->create();
        //});
    //}
}
