<?php

namespace Database\Factories\Admin\Cart;

use App\Models\Admin\Cart\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
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
