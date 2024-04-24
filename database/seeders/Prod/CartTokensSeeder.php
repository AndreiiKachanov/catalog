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
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `carts_tokens`');
            $tokens = json_decode(json_encode($result), true);

            foreach ($tokens as $token) {
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
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
