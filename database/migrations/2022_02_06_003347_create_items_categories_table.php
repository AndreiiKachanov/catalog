<?php

use App\Models\Admin\Item\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateItemsCategoriesTable extends Migration
{
    private string $itemCategoryTableName;

    public function __construct()
    {
        $this->itemCategoryTableName = Category::getTableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->itemCategoryTableName)) {
            Schema::create($this->itemCategoryTableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->string('title')->nullable();
                $table->string('img')->nullable();
                NestedSet::columns($table);
                $table->string('slug', 1200)->nullable();
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
        Schema::dropIfExists($this->itemCategoryTableName);
    }
}
