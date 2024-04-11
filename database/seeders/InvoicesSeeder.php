<?php

namespace Database\Seeders;

use App\Models\Admin\Cart\Invoice;
use App\Models\Admin\Cart\Token;
use App\UseCases\ApiService;
use Exception;
use Illuminate\Database\Seeder;

class InvoicesSeeder extends Seeder
{
    public function __construct(ApiService $service)
    {
        $this->service = $service;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (Invoice::count() != 0) {
            throw new Exception(Invoice::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        try {
            Token::all()
                ->each(fn($token) => Invoice::create([
                    'bill_number' => $this->service->generateInvoiceNumber(),
                    'token_id' => $token->id
                ]));
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
