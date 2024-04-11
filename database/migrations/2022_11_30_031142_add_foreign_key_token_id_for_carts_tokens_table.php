<?php

use App\Models\Admin\Cart\Invoice;
use App\Models\Admin\Cart\Token;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tableName;
    private $cartTokensTableName;

    public function __construct()
    {
        $this->tableName = Invoice::getTableName();
        $this->cartTokensTableName = Token::getTableName();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //создаем  индекс для token_id
            $table->index(['token_id'], 'idx_token_id');

            //создаем внешний ключ для token_id поля
            $table->foreign(['token_id'], 'fk_token_invoice')
                ->references('id')
                ->on($this->cartTokensTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
            if (Schema::hasColumn($this->tableName, 'token_id')) {
                $table->dropForeign('fk_token_invoice');
                $table->dropIndex('idx_token_id');
            }
        });
    }
};
