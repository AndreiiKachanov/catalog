<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Cart\Order\OrderItem;

return new class extends Migration
{
    private $tableName;

    public function __construct()
    {
        $this->tableName = OrderItem::getTableName();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('item_id');
                $table->unsignedSmallInteger('cart_item_id')->nullable();
                $table->unsignedSmallInteger('cnt')->comment('Количество товара');
                $table->unsignedSmallInteger('order_id');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
