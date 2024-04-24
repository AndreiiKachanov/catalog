<?php

namespace Database\Factories\Admin\Cart\Order;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dt = $this->faker->dateTimeBetween('-1 week', 'now');
        return [
            'created_at' => $dt,
            'updated_at' => $dt,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'street' => $this->faker->streetAddress,
            'house_number' => $this->faker->buildingNumber,
            'transport_company' => $this->faker->randomElement(['СДЭК', 'Нова почта', 'Укрпочта'])
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $randomCount = random_int(1, 10);
            OrderItem::factory()
                ->count($randomCount)
                ->create(['order_id' => $order->id]);
        });
    }
}
