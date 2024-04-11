<?php

namespace Database\Seeders;

use App\Models\Admin\Item\Category;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ItemsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::count() != 0) {
            throw new Exception(Category::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        Storage::disk('uploads')->deleteDirectory('categories');
        Storage::disk('uploads')->createDirectory('categories');

        try {
            //папка с картинками
            $dir = 'seeders/seeder_data/categories_img';
            if (!Storage::disk('database')->directoryExists($dir)) {
                throw new Exception('Directory ' . $dir . ' not exists');
            }

            //копируем папку в public
            File::copyDirectory(Storage::disk('database')->path($dir), Storage::disk('uploads')->path('categories'));

            $file = Storage::disk('database')->get('seeders/seeder_data/items_categories_new.json');
            $categories =  json_decode($file, true);
            $data = [];
            foreach ($categories as $category) {
                //$category = $category->getAttributes();
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
