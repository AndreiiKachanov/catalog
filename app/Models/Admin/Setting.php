<?php

namespace App\Models\Admin;

use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin\Setting
 *
 * @property int $id
 * @property string $prop_key
 * @property string $prop_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting wherePropKey($value)
 * @method static Builder|Setting wherePropValue($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @property string $title
 * @method static Builder|Setting whereTitle($value)
 * @property int $is_icon
 * @property string|null $fa_icon
 * @method static Builder|Setting whereFaIcon($value)
 * @method static Builder|Setting whereIsIcon($value)
 * @property int $is_url
 * @method static Builder|Setting whereIsUrl($value)
 * @mixin Eloquent
 */
class Setting extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'settings';
    protected $guarded = ['id'];
}
