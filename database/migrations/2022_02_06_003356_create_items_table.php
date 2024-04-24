<?php

use App\Models\Admin\Item\Item;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    private string $itemsTableName;

    public function __construct()
    {
        $this->itemsTableName = Item::getTableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->itemsTableName)) {
            Schema::create($this->itemsTableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->string('title')->nullable();
                $table->string('note')->nullable();
                $table->string('slug', 1200)->nullable();
                $table->string('article_number')->nullable();
                $table->integer('price')->nullable();
                $table->integer('min_order_amount')->nullable();
                $table->string('img')->nullable();
                $table->boolean('is_new')->default(false);
                $table->boolean('is_hit')->default(false);
                $table->boolean('is_bestseller')->default(false);
                $table->unsignedSmallInteger('category_id')->nullable();
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
        Schema::dropIfExists($this->itemsTableName);
    }
}
