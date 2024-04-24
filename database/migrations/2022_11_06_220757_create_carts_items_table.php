<?php

use App\Models\Admin\Cart\CartItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CartItem::getTableName();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('token_id')->nullable();
                $table->unsignedSmallInteger('item_id')->nullable();
                $table->unsignedSmallInteger('cnt')->comment('Количество товара');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
