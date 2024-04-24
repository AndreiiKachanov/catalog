<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Cart\Order\Order;

return new class extends Migration
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = Order::getTableName();
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
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->string('city')->nullable();
                $table->string('street')->nullable();
                $table->string('house_number')->nullable();
                $table->string('transport_company')->nullable();
                $table->unsignedSmallInteger('token_id')->unique();
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
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
