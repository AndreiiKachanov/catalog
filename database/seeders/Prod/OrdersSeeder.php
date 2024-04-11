<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'orders2';
            //Schema::dropIfExists($newTableName);
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->unsignedSmallInteger('item_id');
            //    $table->unsignedSmallInteger('cnt')->comment('Количество товара');
            //    $table->unsignedSmallInteger('contact_id');
            //    $table->softDeletes();
            //    $table->timestamps();
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для item_id
            //    $table->index(['item_id'], 'idx_item_id2');
            //
            //    //создаем внешний ключ для item_id поля
            //    $table->foreign(['item_id'], 'fk_item_items2')
            //        ->references('id')
            //        ->on('items2')
            //        ->onDelete('restrict')
            //        ->onUpdate('restrict');
            //});

            $result = DB::connection('mysql_prod')->select('SELECT * FROM `orders`');
            $orderItems = json_decode(json_encode($result), true);

            //$orders = OrderItem::on('mysql_prod')->withTrashed()->get();
            foreach ($orderItems as $order) {
//                $test = $this->getCartItemId($order);
//
//                if ($test === null) {
//                    dump($order, $test, 'stop');
//                }
//                $order = $order->getAttributes();
                $arr = [
                    'id' => $order['id'],
                    'item_id' => $order['item_id'],
                    'cart_item_id' => $this->getCartItemId($order),
                    'cnt' => $order['cnt'],
                    'order_id' => $order['contact_id'],
                    'deleted_at' => $order['deleted_at'],
                    'created_at' => $order['created_at'],
                    'updated_at' => $order['updated_at'],
                ];
//                dd($arr);
                DB::table(OrderItem::getTableName())->insert($arr);

                $idsToUpdate = [2767, 2768, 2769, 2770, 2771, 2772, 2773, 2774, 2775, 2776, 2777, 2778, 2779, 2780, 2781, 2782, 2783, 2784, 2785, 2787, 2788, 2789, 2790, 2791, 2792, 2793, 2794, 2795, 2796, 2797, 2798, 2799, 2800, 2801, 2802, 2803, 2804, 3036];
//                CartItem::whereIn('id', $idsToUpdate)->update(['deleted_at' => null]);
                DB::table('cart_items')
                    ->whereIn('id', $idsToUpdate)
                    ->update(['deleted_at' => null]);
            }
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }

    /**
     * @param $orderItem
     * @return mixed
     */
    private function getCartItemId($orderItem)
    {
        $order = Order::withTrashed()->findOrFail($orderItem['contact_id']);
        $tokenId = $order->token->id;

        return DB::table('cart_items as ci')
            ->where('ci.cnt', '=', $orderItem['cnt'])
            ->where('ci.item_id', '=', $orderItem['item_id'])
            ->where('ci.token_id', '=', $tokenId)
//            ->whereNull('ci.deleted_at')
            ->max('ci.id');
    }
}
