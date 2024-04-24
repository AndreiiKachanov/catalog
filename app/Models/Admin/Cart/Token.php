<?php

namespace App\Models\Admin\Cart;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Cart\TokenFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Cart\Token
 *
 * @property int $id
 * @property string $token
 * @property string|null $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $cart_items_count
 * @property-read Order|null $contact
 * @property-read Invoice|null $invoice
 * @method static TokenFactory factory($count = null, $state = [])
 * @method static Builder|Token newModelQuery()
 * @method static Builder|Token newQuery()
 * @method static Builder|Token query()
 * @method static Builder|Token whereCreatedAt($value)
 * @method static Builder|Token whereId($value)
 * @method static Builder|Token whereIp($value)
 * @method static Builder|Token whereToken($value)
 * @method static Builder|Token whereUpdatedAt($value)
 * @property-read Collection<int, CartItem> $cartItems
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $device
 * @property string|null $device_version
 * @property int $is_mobile
 * @property int $is_tablet
 * @property int $is_desktop
 * @property int $is_robot
 * @method static Builder|Token whereBrowser($value)
 * @method static Builder|Token whereDevice($value)
 * @method static Builder|Token whereDeviceVersion($value)
 * @method static Builder|Token whereIsDesktop($value)
 * @method static Builder|Token whereIsMobile($value)
 * @method static Builder|Token whereIsRobot($value)
 * @method static Builder|Token whereIsTablet($value)
 * @method static Builder|Token wherePlatform($value)
 * @property mixed|null $ip_info
 * @method static Builder|Token whereIpInfo($value)
 * @property-read Order|null $order
 * @property int $visits_count
 * @property Carbon|null $last_visit
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static Builder|Token whereLastVisit($value)
 * @method static Builder|Token whereVisitsCount($value)
 * @mixin Eloquent
 */
class Token extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;

    protected $table = 'cart_tokens';
    protected $guarded = ['id'];
    protected $casts = [
        'last_visit' => 'datetime',
    ];


    /**
     * @param $value
     * @return mixed
     */
    public function getIpInfoAttribute($value): mixed
    {
        if ($value !== null) {
            return unserialize($value);
        }
    }

    /**
     * @return HasMany
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'token_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'token_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'token_id', 'id');
    }

    /**
     * @return HasManyThrough
     */
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, CartItem::class);
    }
}
