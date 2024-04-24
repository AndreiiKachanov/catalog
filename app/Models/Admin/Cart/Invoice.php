<?php

namespace App\Models\Admin\Cart;

use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Cart\InvoiceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Cart\Invoice
 *
 * @property int $id
 * @property string $bill_number
 * @property int $token_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Token $token
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereBillNumber($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereTokenId($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @method static InvoiceFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Invoice extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;

    protected $table = 'invoices';
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id', 'id');
    }
}
