<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\User\Permission
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @method static count()
 * @method static create(array $array)
 * @property-read Collection|Role[] $rRoles
 * @property-read int|null $r_roles_count
 * @mixin Eloquent
 */
class Permission extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'permissions';
}
