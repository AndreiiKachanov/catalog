<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Token;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            //$newTableName = 'carts_tokens2';
            //
            //Schema::table('orders_contacts2', function (Blueprint $table) {
            //    if (Schema::hasColumn('orders_contacts2', 'token_id')) {
            //        if (Schema::hasForeign('orders_contacts2', 'fk_token_orders_contacts2')) {
            //            $table->dropForeign('fk_token_orders_contacts2');
            //        }
            //
            //        //$table->dropIndex('idx_token_id2');
            //    }
            //});

            //Schema::table('cart_items2', function (Blueprint $table) {
            //    if (Schema::hasColumn('cart_items2', 'token_id')) {
            //        $table->dropForeign('fk_token2');
            //        $table->dropIndex('idx_token_id2');
            //    }
            //});

            //Schema::dropIfExists($newTableName);
            //
            //Schema::create($newTableName, function (Blueprint $table) {
            //    $table->smallIncrements('id');
            //    $table->string('token', 64)->unique();
            //    $table->ipAddress('ip')->nullable();
            //    $table->timestamps();
            //});

            $result = DB::connection('mysql_prod')->select('SELECT * FROM `carts_tokens`');
            $tokens = json_decode(json_encode($result), true);
            //dd($tokens[0]);
            //$tokens = Token::on('mysql_prod')->get();

            foreach ($tokens as $token) {
                //$token = $token->getAttributes();
                DB::table(Token::getTableName())->insert([
                    'id' => $token['id'],
                    'token' => $token['token'],
                    'ip' => $token['ip'],
                    'ip_info' => $token['ip_info'],
                    'browser' => $token['browser'],
                    'platform' => $token['platform'],
                    'device' => $token['device'],
                    'device_version' => $token['device_version'],
                    'is_mobile' => $token['is_mobile'],
                    'is_tablet' => $token['is_tablet'],
                    'is_desktop' => $token['is_desktop'],
                    'is_robot' => $token['is_robot'],
                    'created_at' => $token['created_at'],
                    'updated_at' => $token['updated_at'],
                ]);
            }
            //$this->command->info('Таблица ' . $newTableName . ' удачно создана и наполнена');
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
