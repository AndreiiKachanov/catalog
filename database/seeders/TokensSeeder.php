<?php

namespace Database\Seeders;

use App\Models\Admin\Cart\Invoice;
use App\Models\Admin\Cart\Token;
use App\UseCases\ApiService;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ApiService $service)
    {
        if (Token::count() != 0) {
            throw new Exception(Token::getTableName() . ' table is not empty. Stop all seeds!!!');
        }
        try {
            //Category::factory()->count(2)->hasChildren(3)->hasItems(1)->create();
            Token::factory()->count(5)->create();
            Token::all()
                ->each(fn($token) => Invoice::create([
                    'bill_number' => $service->generateInvoiceNumber(),
                    'token_id' => $token->id
                ]));
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
