<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Item\Item;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try {
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
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
