<?php

namespace App\Models\Admin\Cart\Order;

use App\Models\Admin\Cart\Token;
use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Cart\Order\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


/**
 * App\Models\Admin\Cart\Order\Contact
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $city
 * @property string|null $street
 * @property string|null $house_number
 * @property string|null $transport_company
 * @property int $token_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, OrderItem> $orders
 * @property-read int|null $orders_count
 * @property-read Token $token
 * @method static OrderFactory factory($count = null, $state = [])
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCity($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereHouseNumber($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereName($value)
 * @method static Builder|Order wherePhone($value)
 * @method static Builder|Order whereStreet($value)
 * @method static Builder|Order whereTokenId($value)
 * @method static Builder|Order whereTransportCompany($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @property-read Collection<int, \App\Models\Admin\Cart\Order\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Collection<int, \App\Models\Admin\Cart\Order\OrderItem> $orderItems
 * @property-read Collection<int, \App\Models\Admin\Cart\Order\OrderItem> $orderItems
 * @property-read Collection<int, \App\Models\Admin\Cart\Order\OrderItem> $orderItems
 * @mixin Eloquent
 */
class Order extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $guarded = ['id'];

    /**
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id', 'id');
    }
}
