<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Invoice;
use App\Models\Admin\Cart\Order\Order;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'orders_contacts2';
            //Schema::dropIfExists($newTableName);
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->string('name')->nullable();
            //    $table->string('phone')->nullable();
            //    $table->string('city')->nullable();
            //    $table->string('street')->nullable();
            //    $table->string('house_number')->nullable();
            //    $table->string('transport_company')->nullable();
            //    $table->unsignedSmallInteger('token_id')->unique();
            //    $table->softDeletes();
            //    $table->timestamps();
            //});
            //
            //Schema::table($newTableName, function (Blueprint $table) {
            //    //создаем  индекс для token_id
            //    $table->index(['token_id'], 'idx_token_id2');
            //
            //    //создаем внешний ключ для token_id поля
            //    $table->foreign(['token_id'], 'fk_token_orders_contacts2')
            //        ->references('id')
            //        ->on('carts_tokens2')
            //        ->onDelete('cascade')
            //        ->onUpdate('cascade');
            //});

            //$contacts = Contact::on('mysql_prod')->withTrashed()->get();
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `orders_contacts`');
            $contacts = json_decode(json_encode($result), true);

            foreach ($contacts as $contact) {
                //$contact = $contact->getAttributes();
                DB::table(Order::getTableName())->insert([
                    'id' => $contact['id'],
                    'name' => $contact['name'],
                    'phone' => $contact['phone'],
                    'city' => $contact['city'],
                    'street' => $contact['street'],
                    'house_number' => $contact['house_number'],
                    'transport_company' => $contact['transport_company'],
                    'token_id' => $contact['token_id'],
                    'deleted_at' => $contact['deleted_at'],
                    'created_at' => $contact['created_at'],
                    'updated_at' => $contact['updated_at'],
                ]);
            }
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
