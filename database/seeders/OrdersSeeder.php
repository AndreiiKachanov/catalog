<?php

namespace Database\Seeders;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use App\Models\Admin\Item\Item;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{

    /**
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (OrderItem::count() != 0) {
            throw new Exception(OrderItem::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        foreach (Order::all() as $order) {
            for ($i = 0; $i <= rand(3, 10); $i++) {
                OrderItem::create([
                    'item_id' => Item::inRandomOrder()->first()->id,
                    'cnt' => rand(1, 5),
                    'order_id' => $order->id
                ]);
            }
        }
    }
}
