<?php

use App\Models\Admin\Item\Item;
use App\Models\Admin\Item\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsForeignKeyCategoryIdForItemsTable extends Migration
{
    private string $itemsTableName;
    private string $itemCategoriesTableName;

    public function __construct()
    {
        $this->itemsTableName = Item::getTableName();
        $this->itemCategoriesTableName = Category::getTableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->itemsTableName, function (Blueprint $table) {
            //создаем  индекс для role_id
            $table->index(['category_id'], 'idx_category_id');

            //создаем внешний ключ для role_id поля
            $table->foreign(['category_id'], 'fk_category')
                ->references('id')
                ->on($this->itemCategoriesTableName)
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->itemsTableName, function (Blueprint $table) {
            if (Schema::hasColumn($this->itemsTableName, 'category_id')) {
                $table->dropForeign('fk_category');
                $table->dropIndex('idx_category_id');
            }
        });
    }
}
