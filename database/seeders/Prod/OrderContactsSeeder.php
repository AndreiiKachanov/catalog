<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Order\Order;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $result = DB::connection('mysql_prod')->select('SELECT * FROM `orders_contacts`');
            $contacts = json_decode(json_encode($result), true);

            foreach ($contacts as $contact) {
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
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
