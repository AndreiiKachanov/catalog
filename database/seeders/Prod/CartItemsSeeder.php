<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\CartItem;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `carts_items`');
            $cartItems = json_decode(json_encode($result), true);

            foreach ($cartItems as $cartItem) {
                DB::table(CartItem::getTableName())->insert([
                    'id' => $cartItem['id'],
                    'token_id' => $cartItem['token_id'],
                    'item_id' => $cartItem['item_id'],
                    'cnt' => $cartItem['cnt'],
                    'deleted_at' => $cartItem['deleted_at'],
                    'created_at' => $cartItem['created_at'],
                    'updated_at' => $cartItem['updated_at']
                ]);
            }
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
