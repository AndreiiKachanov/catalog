<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\User\Role
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $rUsers
 * @property-read int|null $r_users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static count()
 * @method static create(array $array)
 * @method static first()
 * @method static find(int $int)
 * @property-read int|null $posts_count
 * @property-read Collection|Permission[] $rPermissions
 * @property-read int|null $r_permissions_count
 * @mixin Eloquent
 */
class Role extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'roles';

    /**
     * @return HasMany
     */
    public function rUsers(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function rPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, PermissionRoles::getTableName())
                    ->withPivot(['created_at', 'updated_at', 'test'])
                    ->as('membership');
    }
}
