<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `orders`');
            $orderItems = json_decode(json_encode($result), true);

            foreach ($orderItems as $order) {
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
                DB::table(OrderItem::getTableName())->insert($arr);

                $idsToUpdate = [2767, 2768, 2769, 2770, 2771, 2772, 2773, 2774, 2775, 2776, 2777, 2778, 2779, 2780, 2781, 2782, 2783, 2784, 2785, 2787, 2788, 2789, 2790, 2791, 2792, 2793, 2794, 2795, 2796, 2797, 2798, 2799, 2800, 2801, 2802, 2803, 2804, 3036];
                DB::table('cart_items')
                    ->whereIn('id', $idsToUpdate)
                    ->update(['deleted_at' => null]);
            }
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }

    /**
     * @param $orderItem
     * @return mixed
     */
    private function getCartItemId($orderItem): mixed
    {
        $order = Order::withTrashed()->findOrFail($orderItem['contact_id']);
        $tokenId = $order->token->id;

        return DB::table('cart_items as ci')
            ->where('ci.cnt', '=', $orderItem['cnt'])
            ->where('ci.item_id', '=', $orderItem['item_id'])
            ->where('ci.token_id', '=', $tokenId)
            ->max('ci.id');
    }
}
