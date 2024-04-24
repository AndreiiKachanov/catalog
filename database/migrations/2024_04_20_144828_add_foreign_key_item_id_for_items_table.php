<?php

use App\Models\Admin\Item\Item;
use App\Models\Admin\PriceHistoryItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $itemTabName;
    private string $priceHistoryItemTabName;

    public function __construct()
    {
        $this->itemTabName = Item::getTableName();
        $this->priceHistoryItemTabName = PriceHistoryItem::getTableName();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->priceHistoryItemTabName, function (Blueprint $table) {
            //создаем  индекс для item_id
            $table->index(['item_id'], 'idx_items_item_id');

            //создаем внешний ключ для item_id поля
            $table->foreign(['item_id'], 'fk_items_item_id')
                ->references('id')
                ->on($this->itemTabName)
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->priceHistoryItemTabName, function (Blueprint $table) {
            if (Schema::hasColumn($this->priceHistoryItemTabName, 'item_id')) {
                $table->dropForeign('fk_items_item_id');
                $table->dropIndex('idx_items_item_id');
            }
        });
    }
};
