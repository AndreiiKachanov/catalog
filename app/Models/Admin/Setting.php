<?php

namespace App\Models\Admin;

use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Admin\Setting
 *
 * @property int $id
 * @property string $prop_key
 * @property string $prop_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePropKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePropValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTitle($value)
 * @property int $is_icon
 * @property string|null $fa_icon
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFaIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereIsIcon($value)
 * @property int $is_url
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereIsUrl($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'settings';
    protected $guarded = ['id'];
}
