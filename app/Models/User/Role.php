<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $rUsers
 * @property-read int|null $r_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereUpdatedAt($value)
 * @method static count()
 * @method static create(array $array)
 * @method static first()
 * @method static find(int $int)
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Permission[] $rPermissions
 * @property-read int|null $r_permissions_count
 * @mixin \Eloquent
 */
class Role extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'roles';

    public function rUsers()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rPermissions()
    {
        return $this->belongsToMany(Permission::class, PermissionRoles::getTableName())
                    ->withPivot(['created_at', 'updated_at', 'test'])
                    ->as('membership');
    }
}
