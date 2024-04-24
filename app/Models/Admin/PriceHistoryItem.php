<?php

namespace App\Models\Admin;

use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\PriceHistoryItem
 *
 * @property int $id
 * @property int|null $item_id
 * @property int|null $price_history_id
 * @property int|null $old_price
 * @property int|null $new_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PriceHistory|null $priceHistory
 * @method static Builder|PriceHistoryItem newModelQuery()
 * @method static Builder|PriceHistoryItem newQuery()
 * @method static Builder|PriceHistoryItem query()
 * @method static Builder|PriceHistoryItem whereCreatedAt($value)
 * @method static Builder|PriceHistoryItem whereId($value)
 * @method static Builder|PriceHistoryItem whereItemId($value)
 * @method static Builder|PriceHistoryItem whereNewPrice($value)
 * @method static Builder|PriceHistoryItem whereOldPrice($value)
 * @method static Builder|PriceHistoryItem wherePriceHistoryId($value)
 * @method static Builder|PriceHistoryItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PriceHistoryItem extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'price_history_items';
    protected $guarded = ['id'];
    protected $fillable = ['item_id', 'old_price', 'new_price'];

    /**
     * @return BelongsTo
     */
    public function priceHistory(): BelongsTo
    {
        return $this->belongsTo(PriceHistory::class);
    }
}
