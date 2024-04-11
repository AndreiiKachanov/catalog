<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Invoice;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'invoices2';
            //Schema::dropIfExists($newTableName);
            //Schema::table($newTableName, function (Blueprint $table) use ($newTableName) {
            //    if (Schema::hasColumn($newTableName, 'token_id')) {
            //        $table->dropForeign('fk_token_invoice');
            //        $table->dropIndex('idx_token_id');
            //    }
            //});
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->string('bill_number', 14)->unique();
            //    $table->unsignedSmallInteger('token_id');
            //    $table->timestamps();
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для token_id
            //    $table->index(['token_id'], 'idx_token_id2');
            //
            //    //создаем внешний ключ для token_id поля
            //    $table->foreign(['token_id'], 'fk_token_invoice2')
            //        ->references('id')
            //        ->on('carts_tokens2')
            //        ->onDelete('cascade')
            //        ->onUpdate('cascade');
            //});



            $invoices = Invoice::on('mysql_prod')->get();

            foreach ($invoices as $invoice) {
                DB::table(Invoice::getTableName())->insert([
                    'id' => $invoice->id,
                    'bill_number' => $invoice->bill_number,
                    'token_id' => $invoice->token_id,
                    'created_at' => $invoice->created_at,
                    'updated_at' => $invoice->updated_at,
                ]);
            }
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
