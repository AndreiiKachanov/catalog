<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Cart\Invoice;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try {
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
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
