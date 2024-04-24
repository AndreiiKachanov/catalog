<?php

use App\Models\Admin\PriceHistory;
use App\Models\Admin\PriceHistoryItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $priceHistoryTabName;
    private string $priceHistoryItemTabName;

    public function __construct()
    {
        $this->priceHistoryTabName = PriceHistory::getTableName();
        $this->priceHistoryItemTabName = PriceHistoryItem::getTableName();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->priceHistoryItemTabName, function (Blueprint $table) {
            //создаем  индекс для item_id
            $table->index(['price_history_id'], 'idx_price_histories_price_history_id');

            //создаем внешний ключ для item_id поля
            $table->foreign(['price_history_id'], 'fk_price_histories_price_history_id')
                ->references('id')
                ->on($this->priceHistoryTabName)
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
            if (Schema::hasColumn($this->priceHistoryItemTabName, 'price_history_id')) {
                $table->dropForeign('fk_price_histories_price_history_id');
                $table->dropIndex('idx_price_histories_price_history_id');
            }
        });
    }
};
