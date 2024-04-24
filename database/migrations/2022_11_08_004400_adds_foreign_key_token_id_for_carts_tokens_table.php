<?php

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Token;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName;
    private string $cartTokensTableName;

    public function __construct()
    {
        $this->tableName = CartItem::getTableName();
        $this->cartTokensTableName = Token::getTableName();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //создаем  индекс для item_id
            $table->index(['token_id'], 'idx_token_id');

            //создаем внешний ключ для item_id поля
            $table->foreign(['token_id'], 'fk_token')
                ->references('id')
                ->on($this->cartTokensTableName)
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            if (Schema::hasColumn($this->tableName, 'token_id')) {
                $table->dropForeign('fk_token');
                $table->dropIndex('idx_token_id');
            }
        });
    }
};
