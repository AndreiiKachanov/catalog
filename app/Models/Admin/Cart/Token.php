<?php

namespace App\Models\Admin\Cart;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Order\OrderItem;
use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * App\Models\Admin\Cart\Token
 *
 * @property int $id
 * @property string $token
 * @property string|null $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $cart_items_count
 * @property-read Order|null $contact
 * @property-read \App\Models\Admin\Cart\Invoice|null $invoice
 * @method static \Database\Factories\Admin\Cart\TokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Cart\CartItem> $cartItems
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $device
 * @property string|null $device_version
 * @property int $is_mobile
 * @property int $is_tablet
 * @property int $is_desktop
 * @property int $is_robot
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereDeviceVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIsDesktop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIsMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIsRobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIsTablet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token wherePlatform($value)
 * @property mixed|null $ip_info
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIpInfo($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Cart\CartItem> $cartItems
 * @property-read Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Cart\CartItem> $cartItems
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Cart\CartItem> $cartItems
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Cart\CartItem> $cartItems
 * @property int $visits_count
 * @property \Illuminate\Support\Carbon|null $last_visit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereLastVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereVisitsCount($value)
 * @mixin \Eloquent
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
    public function getIpInfoAttribute($value)
    {
        //dump($value);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, CartItem::class);
    }
}
