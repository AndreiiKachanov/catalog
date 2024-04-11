<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\User\Permission
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Permission whereUpdatedAt($value)
 * @method static count()
 * @method static create(array $array)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Role[] $rRoles
 * @property-read int|null $r_roles_count
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'permissions';

    public function rRoles()
    {
        return $this->belongsToMany(Role::class, PermissionRoles::getTableName())
            ->withPivot(['created_at', 'updated_at', 'test'])
            ->as('membership');
    }
}
