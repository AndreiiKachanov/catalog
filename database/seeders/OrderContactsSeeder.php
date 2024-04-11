<?php

namespace Database\Seeders;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use App\Models\Admin\Cart\Token;
use App\UseCases\ApiService;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderContactsSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Order::count() != 0) {
            throw new Exception(Order::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        try {

            //Token::factory()->count(10)->create();

            //for ($i = 1; $i <= 100; $i++) {
                //$ordersCnt = rand(1, 10);
                Token::factory()->count(300)->create();
                //Order::factory()->for($token)->count(1)->create();
            //}
            //Contact::factory()
            //    ->hasOrders(5)
            //    //->for($token)
            //    ->count(10)
            //    ->create();

            //foreach (Token::all() as $token) {
            //    $contact = new Contact();
            //    $contact->name = 'Grishka';
            //    $contact->contact = '+794954788641';
            //    $contact->token_id = $token->id;
            //    $contact->save();
            //}

        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
