<?php

use App\Models\User\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    private string $permissionsTableName;

    public function __construct()
    {
        $this->permissionsTableName = Permission::getTableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable($this->permissionsTableName)) {
            Schema::create($this->permissionsTableName, function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->string('name');
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
        Schema::dropIfExists($this->permissionsTableName);
    }
}
