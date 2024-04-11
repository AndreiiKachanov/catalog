<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Setting;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $items = User::on('mysql_prod')->get();
            foreach ($items as $item) {
                $item = $item->getAttributes();
                DB::table(User::getTableName())->insert([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    //'password' => $item['password'],
                    'password' => Hash::make('qwerty123'),
                    //'email' => $item['email'],
                    'email' => 'test@test.loc',
                    'email_verified_at' => $item['email_verified_at'],
                    'role_id' => $item['role_id'],
                    'phone' => $item['phone'],
                    'phone_auth' => $item['phone_auth'],
                    'phone_verified' => $item['phone_verified'],
                    'phone_verify_token' => $item['phone_verify_token'],
                    'phone_verify_token_expire' => $item['phone_verify_token_expire'],
                    'remember_token' => $item['remember_token'],
                    'status' => $item['status'],
                    'verify_token' => $item['verify_token'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at']
                ]);
            }
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
