<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Item\Category;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `items_categories`');
            $categories = json_decode(json_encode($result), true);

            $data = [];
            foreach ($categories as $category) {
                   $arr['id'] = $category['id'];
                   $arr['title'] =  $category['title'];
                   $arr['img'] = $category['img'];
                   $arr['_lft'] = $category['_lft'];
                   $arr['_rgt'] = $category['_rgt'];
                   $arr['slug'] = $category['slug'];
                   $arr['deleted_at'] = $category['deleted_at'];
                   $arr['created_at'] = $category['created_at'];
                   $arr['updated_at'] = $category['updated_at'];
                   $arr['parent_id'] = $category['parent_id'];
                   $data[] = $arr;
            }
            DB::table(Category::getTableName())->insert($data);
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
