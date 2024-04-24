<?php

use App\Models\Admin\PriceHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = PriceHistory::getTableName();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->smallInteger('percent')->nullable();
                $table->timestamp('price_updated_at')->nullable();
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
