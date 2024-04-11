<?php

namespace App\Models\Admin\Cart\Order;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Item\Item;
use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Admin\Cart\Order\Order
 *
 * @property int $id
 * @property int $item_id
 * @property int $cnt Количество товара
 * @property int $contact_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin\Cart\Order\Order $contact
 * @property-read Item $item
 * @method static \Database\Factories\Admin\Cart\Order\OrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem withoutTrashed()
 * @property int $order_id
 * @property-read \App\Models\Admin\Cart\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @property int|null $cart_item_id
 * @property-read CartItem|null $cartItem
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCartItemId($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_items';
    protected $guarded = ['id'];
    protected $casts = [
        //'created_at'  => 'datetime:d.m.Y',
        //'created_at'  => 'datetime:Y-m-d H:m',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    public function cartItem(): BelongsTo
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }
}
