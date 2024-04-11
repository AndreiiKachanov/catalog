<?php

namespace App\Models\Admin\Item;

use App\Models\Admin\Cart\Order\OrderItem;
use App\Traits\EloquentGetTableNameTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Carbon;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\QueryBuilder;


/**
 * App\Models\Admin\Item\Category
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $img
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property string|null $slug
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Collection<int, Category> $childrens
 * @property-read int|null $childrens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin\Item\Item> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Admin\Item\Item|null $latestContact
 * @property-read \App\Models\Admin\Item\Item|null $mostPrice
 * @property-read \App\Models\Admin\Item\Item|null $mostPriceArticleNumber
 * @property-read \App\Models\Admin\Item\Item|null $oldestContact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Category|null $parent
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static QueryBuilder|Category ancestorsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|Category ancestorsOf($id, array $columns = [])
 * @method static QueryBuilder|Category applyNestedSetScope(?string $table = null)
 * @method static QueryBuilder|Category countErrors()
 * @method static QueryBuilder|Category d()
 * @method static QueryBuilder|Category defaultOrder(string $dir = 'asc')
 * @method static QueryBuilder|Category descendantsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|Category descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Database\Factories\Admin\Item\CategoryFactory factory($count = null, $state = [])
 * @method static QueryBuilder|Category findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static QueryBuilder|Category fixSubtree($root)
 * @method static QueryBuilder|Category fixTree($root = null)
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static QueryBuilder|Category getNodeData($id, $required = false)
 * @method static QueryBuilder|Category getPlainNodeData($id, $required = false)
 * @method static QueryBuilder|Category getTotalErrors()
 * @method static QueryBuilder|Category hasChildren()
 * @method static QueryBuilder|Category hasParent()
 * @method static QueryBuilder|Category isBroken()
 * @method static QueryBuilder|Category leaves(array $columns = [])
 * @method static QueryBuilder|Category makeGap(int $cut, int $height)
 * @method static QueryBuilder|Category moveNode($key, $position)
 * @method static QueryBuilder|Category newModelQuery()
 * @method static QueryBuilder|Category newQuery()
 * @method static Builder|Category onlyTrashed()
 * @method static QueryBuilder|Category orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static QueryBuilder|Category orWhereDescendantOf($id)
 * @method static QueryBuilder|Category orWhereNodeBetween($values)
 * @method static QueryBuilder|Category orWhereNotDescendantOf($id)
 * @method static QueryBuilder|Category query()
 * @method static QueryBuilder|Category rebuildSubtree($root, array $data, $delete = false)
 * @method static QueryBuilder|Category rebuildTree(array $data, $delete = false, $root = null)
 * @method static QueryBuilder|Category reversed()
 * @method static QueryBuilder|Category root(array $columns = [])
 * @method static QueryBuilder|Category whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static QueryBuilder|Category whereAncestorOrSelf($id)
 * @method static QueryBuilder|Category whereCreatedAt($value)
 * @method static QueryBuilder|Category whereDeletedAt($value)
 * @method static QueryBuilder|Category whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static QueryBuilder|Category whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static QueryBuilder|Category whereId($value)
 * @method static QueryBuilder|Category whereImg($value)
 * @method static QueryBuilder|Category whereIsAfter($id, $boolean = 'and')
 * @method static QueryBuilder|Category whereIsBefore($id, $boolean = 'and')
 * @method static QueryBuilder|Category whereIsLeaf()
 * @method static QueryBuilder|Category whereIsRoot()
 * @method static QueryBuilder|Category whereLft($value)
 * @method static QueryBuilder|Category whereNodeBetween($values, $boolean = 'and', $not = false)
 * @method static QueryBuilder|Category whereNotDescendantOf($id)
 * @method static QueryBuilder|Category whereParentId($value)
 * @method static QueryBuilder|Category whereRgt($value)
 * @method static QueryBuilder|Category whereSlug($value)
 * @method static QueryBuilder|Category whereTitle($value)
 * @method static QueryBuilder|Category whereUpdatedAt($value)
 * @method static QueryBuilder|Category withDepth(string $as = 'depth')
 * @method static Builder|Category withTrashed()
 * @method static QueryBuilder|Category withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static QueryBuilder|Category withoutRoot()
 * @method static Builder|Category withoutTrashed()
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @mixin Eloquent
 */
class Category extends Model
{
    use EloquentGetTableNameTrait;
    use SoftDeletes;
    use HasFactory;
    //use Sluggable;
    //use HasRecursiveRelationships;
    //use NodeTrait;

    use Sluggable, NodeTrait {
        NodeTrait::replicate as replicateNode;
        Sluggable::replicate as replicateSlug;
    }

    protected $table = 'item_categories';
    protected $guarded = ['id'];
    protected $casts = [
        'parent_id' => 'integer',
        'sorting' => 'integer',
        'created_at'  => 'datetime:d.m.Y H:i:s',
    ];

    public function replicate(array $except = null): Model|Category
    {
        $instance = $this->replicateNode($except);
        (new SlugService())->slug($instance, true);

        return $instance;
    }

    /**
     * @return \string[][]
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
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    // Заказы, принадлежащие данной категории
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, Item::class);
    }

    public function latestContact(): HasOne
    {
        return $this->hasOne(Item::class, 'category_id', 'id')->latestOfMany();
    }

    public function oldestContact(): HasOne
    {
        return $this->hasOne(Item::class, 'category_id', 'id')->oldestOfMany();
    }

    public function mostPrice(): HasOne
    {
        return $this->hasOne(Item::class, 'category_id', 'id')->ofMany('price', 'max');
    }

    public function mostPriceArticleNumber(): HasOne
    {
        return $this->hasOne(Item::class, 'category_id', 'id')->ofMany([
            'price' => 'max'
        ], function ($query) {
            $query->whereArticleNumber('22363');
        });
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    //public function recursiveItems(): \Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants
    //{
    //    //return $this->hasManyOfDescendantsAndSelf(Item::class);
    //    return $this->hasManyOfDescendants(Item::class);
    //}

    //public function recursiveItemsNotSelf(): \Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants
    //{
    //    return $this->hasManyOfDescendants(Item::class);
    //}
}
