<?php

namespace App\Models\Admin;

use App\Models\Admin\Item\Item;
use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\PriceHistory
 *
 * @property int $id
 * @property int|null $percent
 * @property Carbon|null $price_updated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Item> $items
 * @property-read int|null $items_count
 * @property-read Collection<int, PriceHistoryItem> $priceHistoryItems
 * @property-read int|null $price_history_items_count
 * @method static Builder|PriceHistory newModelQuery()
 * @method static Builder|PriceHistory newQuery()
 * @method static Builder|PriceHistory query()
 * @method static Builder|PriceHistory whereCreatedAt($value)
 * @method static Builder|PriceHistory whereId($value)
 * @method static Builder|PriceHistory wherePercent($value)
 * @method static Builder|PriceHistory wherePriceUpdatedAt($value)
 * @method static Builder|PriceHistory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PriceHistory extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'price_histories';
    protected $guarded = ['id'];
    protected $casts = [
        'price_updated_at'  => 'datetime:d.m.Y H:i:s',
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * @return HasMany
     */
    public function priceHistoryItems(): HasMany
    {
        return $this->hasMany(PriceHistoryItem::class);
    }
}
