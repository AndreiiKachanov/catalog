<?php

namespace Database\Seeders;

use App\Models\Admin\Item\Category;
use App\Models\Admin\Item\Item;
use Exception;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Item::count() != 0) {
            throw new Exception(Item::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        Storage::disk('uploads')->deleteDirectory('items');
        Storage::disk('uploads')->createDirectory('items');

        //Переливаем данные из json в бд
        try {
            //папка с картинками
            $dir = 'seeders/seeder_data/items_img';
            if (!Storage::disk('database')->directoryExists($dir)) {
                throw new Exception('Directory ' . $dir . ' not exists');
            }
            //копируем папку в public
            File::copyDirectory(Storage::disk('database')->path($dir), Storage::disk('uploads')->path('items'));

            //$file = Storage::disk('database')->get('seeders/seeder_data/items.json');
            $file = Storage::disk('database')->get('seeders/seeder_data/items_new.json');
            $json = json_decode($file);
            foreach ($json as $item) {
                //if (!isset($item->deleted_at)) {
                    Item::create([
                        'title' =>  $item->title,
                        'note' => $item->note,
                        'slug' => $item->slug,
                        'article_number' => $item->article_number,
                        'price' => $item->price,
                        'min_order_amount' => $item->min_order_amount,
                        'img' => $item->img,
                        'is_new' => $item->is_new,
                        'is_hit' => $item->is_hit,
                        'is_bestseller' => $item->is_bestseller,
                        'category_id' => $item->category_id,
                        'deleted_at' => $item->deleted_at,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at
                    ]);
                //}
            }
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }

        ////Создание айтемов с помощью фабрик
        //try {
        //    //по 4 итема привязать к родительской категории
        //    $countParent = 500;
        //    $parentCategories = Category::whereParentId(null)->get();
        //    $parentItemIds = [];
        //    foreach ($parentCategories as $parent) {
        //        for ($i = 1; $i <=  $countParent; $i++) {
        //            $parentItemIds[] = $parent->id;
        //        }
        //    }
        //
        //    Item::factory()
        //        ->count(count($parentItemIds))
        //        ->state(new Sequence(fn($sequence) => ['category_id' => $parentItemIds[$sequence->index]]))
        //        ->create();
        //
        //    //по 2 итемов привязать к дочерним категориям
        //    $countSub = 30;
        //    $subCategories = Category::whereNotNull('parent_id')->get();
        //    $categoryItemIds = [];
        //    foreach ($subCategories as $category) {
        //        for ($i = 1; $i <= $countSub; $i++) {
        //            $categoryItemIds[] = $category->id;
        //        }
        //    }
        //    Item::factory()
        //        ->count(count($categoryItemIds))
        //        ->state(new Sequence(fn($sequence) => ['category_id' => $categoryItemIds[$sequence->index]]))
        //        ->create();
        //
        //    //Update article_number
        //    $items = Item::whereIn('category_id', Category::whereNotNull('parent_id')->pluck('id')->toArray())->get();
        //    foreach ($items as $item) {
        //        $new = $item->id . '.' . $item->article_number;
        //        $item->update(['article_number' => $new]);
        //    }
        //} catch (Exception $e) {
        //    $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
        //    throw new Exception($errorMsg);
        //}
    }
}
