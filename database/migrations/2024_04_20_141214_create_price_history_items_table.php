<?php

use App\Models\Admin\PriceHistoryItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = PriceHistoryItem::getTableName();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('item_id')->nullable();
                $table->unsignedSmallInteger('price_history_id')->nullable();
                $table->integer('old_price')->nullable();
                $table->integer('new_price')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
