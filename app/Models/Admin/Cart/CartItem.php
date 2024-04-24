<?php

namespace App\Models\Admin\Cart;

use App\Models\Admin\Cart\Order\OrderItem;
use App\Models\Admin\Item\Item;
use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Cart\CartItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Cart\CartItem
 *
 * @property int $id
 * @property int $token_id
 * @property int $item_id
 * @property int $cnt Количество товара
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Item $item
 * @property-read Token $token
 * @method static Builder|CartItem newModelQuery()
 * @method static Builder|CartItem newQuery()
 * @method static Builder|CartItem onlyTrashed()
 * @method static Builder|CartItem query()
 * @method static Builder|CartItem whereCnt($value)
 * @method static Builder|CartItem whereCreatedAt($value)
 * @method static Builder|CartItem whereDeletedAt($value)
 * @method static Builder|CartItem whereId($value)
 * @method static Builder|CartItem whereItemId($value)
 * @method static Builder|CartItem whereTokenId($value)
 * @method static Builder|CartItem whereUpdatedAt($value)
 * @method static Builder|CartItem withTrashed()
 * @method static Builder|CartItem withoutTrashed()
 * @property-read OrderItem|null $orderItem
 * @method static CartItemFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class CartItem extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cart_items';
    protected $guarded = ['id'];
    protected $casts = ['cnt' => 'integer'];

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function orderItem(): HasOne
    {
        return $this->hasOne(OrderItem::class, 'cart_item_id');
    }
}
