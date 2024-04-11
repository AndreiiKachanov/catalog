<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Item\Item;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'items2';
            //Schema::dropIfExists($newTableName);
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->string('title')->nullable();
            //    $table->string('note')->nullable();
            //    $table->string('slug', 1200)->nullable();
            //    $table->string('article_number')->nullable();
            //    $table->integer('price')->nullable();
            //    $table->integer('min_order_amount')->nullable();
            //    $table->string('img')->nullable();
            //    $table->boolean('is_new')->default(false);
            //    $table->boolean('is_hit')->default(false);
            //    $table->boolean('is_bestseller')->default(false);
            //    $table->unsignedSmallInteger('category_id')->nullable();
            //    $table->softDeletes();
            //    $table->timestamps();
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для role_id
            //    $table->index(['category_id'], 'idx_category_id2');
            //
            //    //создаем внешний ключ для role_id поля
            //    $table->foreign(['category_id'], 'fk_category2')
            //        ->references('id')
            //        ->on('items_categories2')
            //        ->onDelete('set null')
            //        ->onUpdate('set null');
            //});

            $items = Item::on('mysql_prod')->withTrashed()->get();
            foreach ($items as $item) {
                $item = $item->getAttributes();
                DB::table(Item::getTableName())->insert([
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'note' => $item['note'],
                    'slug' => $item['slug'],
                    'article_number' => $item['article_number'],
                    'price' => $item['price'],
                    'min_order_amount' => $item['min_order_amount'],
                    'img' => $item['img'],
                    'is_new' => $item['is_new'],
                    'is_hit' => $item['is_hit'],
                    'is_bestseller' => $item['is_bestseller'],
                    'category_id' => $item['category_id'],
                    'deleted_at' => $item['deleted_at'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at']
                ]);
            }
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
