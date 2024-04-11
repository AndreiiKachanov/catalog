<?php

namespace Database\Factories\Admin\Cart;

use App\Models\Admin\Cart\Invoice;
use App\UseCases\ApiService;
use App\UseCases\SendOrderService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Cart\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'bill_number' => date('Y') . '-' . str_pad(Invoice::max('id') * 33, 6, '0', STR_PAD_LEFT)
        ];
    }
}
