<?php

namespace Database\Seeders;

use App\Models\Admin\Item\Category;
use App\Models\Admin\Item\Item;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ItemsCategorySeederFactory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() != 0) {
            throw new Exception(Category::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        Storage::disk('uploads')->deleteDirectory('categories');
        Storage::disk('uploads')->deleteDirectory('items');

        Storage::disk('uploads')->createDirectory('categories');
        Storage::disk('uploads')->createDirectory('items');

        try {
            //Category::factory()->count(2)->hasChildren(3)->hasItems(1)->create();

            // Создаем родительскую категорию и дочерние категории
            $randomCount = random_int(3, 7);
            //Category::factory()->count(10)->hasChildren(2)->create();
            Category::factory()->count(10)->hasChildren($randomCount)->create()->each(function ($parentCategory) {

                // Создаем элементы items для родительской категории
                Item::factory()->count(10)->create([
                    'category_id' => $parentCategory->id,
                ]);
                dump('new');
                //
                // Создаем элементы items для дочерних категорий
                $parentCategory->children()->each(function ($childCategory) {
                    Item::factory()->count(15)->create([
                        'category_id' => $childCategory->id,
                    ]);
                });
            });

        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
