<?php

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Order\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    private $tableName;
    private $cartItemsTableName;

    public function __construct()
    {
        $this->tableName = OrderItem::getTableName();
        $this->cartItemsTableName = CartItem::getTableName();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //создаем  индекс для order_id
            $table->index(['cart_item_id'], 'idx_cart_item_id');

            //создаем внешний ключ для item_id поля
            $table->foreign(['cart_item_id'], 'fk_cart_item')
                ->references('id')
                ->on($this->cartItemsTableName)
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            if (Schema::hasColumn($this->tableName, 'order_id')) {
                $table->dropForeign('fk_cart_item');
                $table->dropIndex('idx_cart_item_id');
            }
        });
    }
};
