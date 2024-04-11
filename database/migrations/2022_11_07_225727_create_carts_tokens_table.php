<?php

use App\Models\Admin\Cart\Token;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tableName;

    public function __construct()
    {
        $this->tableName = Token::getTableName();
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
                $table->string('token', 64)->unique();
                $table->ipAddress('ip')->nullable();
                $table->text('ip_info')->nullable()->comment('Текстовый столбец для хранения сериализованного массива');
                $table->string('browser')->nullable();
                $table->string('platform')->nullable();
                $table->string('device')->nullable();
                $table->string('device_version')->nullable();
                $table->boolean('is_mobile')->default(false);
                $table->boolean('is_tablet')->default(false);
                $table->boolean('is_desktop')->default(false);
                $table->boolean('is_robot')->default(false);
                $table->integer('visits_count')->default(0);
                $table->timestamp('last_visit')->nullable();
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
        //dd($this->tableName);
        Schema::dropIfExists($this->tableName);
    }
};
