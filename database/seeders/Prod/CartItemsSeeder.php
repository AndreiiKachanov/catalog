<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\CartItem;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'cart_items2';
            //Schema::dropIfExists($newTableName);
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->unsignedSmallInteger('token_id');
            //    $table->unsignedSmallInteger('item_id');
            //    $table->unsignedSmallInteger('cnt')->comment('Количество товара');
            //    $table->timestamps();
            //    $table->softDeletes();
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для item_id
            //    $table->index(['item_id'], 'idx_item_id2');
            //
            //    //создаем внешний ключ для item_id поля
            //    $table->foreign(['item_id'], 'fk_item2')
            //        ->references('id')
            //        ->on('items2')
            //        ->onDelete('restrict')
            //        ->onUpdate('restrict');
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для item_id
            //    $table->index(['token_id'], 'idx_token_id2');
            //
            //    //создаем внешний ключ для item_id поля
            //    $table->foreign(['token_id'], 'fk_token2')
            //        ->references('id')
            //        ->on('carts_tokens2')
            //        ->onDelete('restrict')
            //        ->onUpdate('restrict');
            //});

            //$cartItems = CartItem::on('mysql_prod')->withTrashed()->get();
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `carts_items`');
            $cartItems = json_decode(json_encode($result), true);

            foreach ($cartItems as $cartItem) {
                //$cartItem = $cartItem->getAttributes();
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
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
