<?php

namespace Database\Seeders;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Token;
use Exception;
use Illuminate\Database\Seeder;

class OrderContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        if (Order::count() != 0) {
            throw new Exception(Order::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        try {
            Token::factory()->count(300)->create();
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
