<?php

namespace App\Models\Admin\Item;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Order\OrderItem;
use App\Services\SettingsService;
use App\Traits\EloquentGetTableNameTrait;
use Database\Factories\Admin\Item\ItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Item\Item
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $note
 * @property string|null $slug
 * @property string|null $article_number
 * @property int|null $price
 * @property int|null $min_order_amount
 * @property string|null $img
 * @property bool $is_new
 * @property bool $is_hit
 * @property bool $is_bestseller
 * @property int|null $category_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $cart_items_count
 * @property-read Category|null $category
 * @property-read int|null $order_items_count
 * @method static ItemFactory factory($count = null, $state = [])
 * @method static Builder|Item findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item onlyTrashed()
 * @method static Builder|Item query()
 * @method static Builder|Item whereArticleNumber($value)
 * @method static Builder|Item whereCategoryId($value)
 * @method static Builder|Item whereCreatedAt($value)
 * @method static Builder|Item whereDeletedAt($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereImg($value)
 * @method static Builder|Item whereIsBestseller($value)
 * @method static Builder|Item whereIsHit($value)
 * @method static Builder|Item whereIsNew($value)
 * @method static Builder|Item whereMinOrderAmount($value)
 * @method static Builder|Item whereNote($value)
 * @method static Builder|Item wherePrice($value)
 * @method static Builder|Item whereSlug($value)
 * @method static Builder|Item whereTitle($value)
 * @method static Builder|Item whereUpdatedAt($value)
 * @method static Builder|Item withTrashed()
 * @method static Builder|Item withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 * @method static Builder|Item withoutTrashed()
 * @property-read Collection<int, CartItem> $cartItems
 * @property-read Collection<int, OrderItem> $orderItems
 * @mixin Eloquent
 */
class Item extends Model
{
    use EloquentGetTableNameTrait;
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $table = 'items';
    protected $guarded = ['id'];
    protected $casts = [
        'is_new' => 'boolean',
        'is_hit' => 'boolean',
        'is_bestseller' => 'boolean'
    ];

    /**
     * @return array[]
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasManyThrough
     */
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, CartItem::class);
    }

    /**
     * @return Attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $priceIncrease = SettingsService::getPriceIncrease();
                $price = ($value / 100) * $priceIncrease + $value;
                return round($price); // Округляем до целого числа
            }
        );
    }
}
