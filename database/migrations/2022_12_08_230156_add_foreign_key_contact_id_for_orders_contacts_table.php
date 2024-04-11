<?php

use App\Models\Admin\Cart\Order\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Cart\Order\OrderItem;

return new class extends Migration
{
    private $tableName;
    private $orderTableName;

    public function __construct()
    {
        $this->tableName = OrderItem::getTableName();
        $this->orderTableName = Order::getTableName();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //создаем  индекс для order_id
            $table->index(['order_id'], 'idx_order_id');

            //создаем внешний ключ для item_id поля
            $table->foreign(['order_id'], 'fk_order')
                ->references('id')
                ->on($this->orderTableName)
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            if (Schema::hasColumn($this->tableName, 'order_id')) {
                $table->dropForeign('fk_order');
                $table->dropIndex('idx_order_id');
            }
        });
    }
};
