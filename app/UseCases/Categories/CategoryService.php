<?php

namespace App\UseCases\Categories;

use App\Models\Admin\Item\Category;

class CategoryService
{

    /**
     * @return array
     */
    public static function getCategoriesWithTree(): array
    {
        $categories = Category::defaultOrder()->withDepth()->get()->each(
            function (Category $category) {
                $str = '';
                for ($i = 0; $i < $category->depth; $i++) {
                    $str .= html_entity_decode('&mdash; ', 0, "UTF-8");
                }
                $category->title = $str . $category->title;
            }
        )->pluck('title', 'id')->toArray();

        return $categories;
    }
}
