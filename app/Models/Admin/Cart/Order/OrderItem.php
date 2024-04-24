<?php

namespace App\Models\Admin\Cart\Order;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Item\Item;
use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Cart\Order\OrderItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Cart\Order\Order
 *
 * @property int $id
 * @property int $item_id
 * @property int $cnt Количество товара
 * @property int $contact_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $contact
 * @property-read Item $item
 * @method static OrderItemFactory factory($count = null, $state = [])
 * @method static Builder|OrderItem newModelQuery()
 * @method static Builder|OrderItem newQuery()
 * @method static Builder|OrderItem onlyTrashed()
 * @method static Builder|OrderItem query()
 * @method static Builder|OrderItem whereCnt($value)
 * @method static Builder|OrderItem whereContactId($value)
 * @method static Builder|OrderItem whereCreatedAt($value)
 * @method static Builder|OrderItem whereDeletedAt($value)
 * @method static Builder|OrderItem whereId($value)
 * @method static Builder|OrderItem whereItemId($value)
 * @method static Builder|OrderItem whereUpdatedAt($value)
 * @method static Builder|OrderItem withTrashed()
 * @method static Builder|OrderItem withoutTrashed()
 * @property int $order_id
 * @property-read Order $order
 * @method static Builder|OrderItem whereOrderId($value)
 * @property int|null $cart_item_id
 * @property-read CartItem|null $cartItem
 * @method static Builder|OrderItem whereCartItemId($value)
 * @mixin Eloquent
 */
class OrderItem extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_items';
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function cartItem(): BelongsTo
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }
}
