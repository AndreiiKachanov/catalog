<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\User\PermissionRoles
 *
 * @method static count()
 * @property int $permission_id
 * @property int $role_id
 * @property string|null $test
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PermissionRoles newModelQuery()
 * @method static Builder|PermissionRoles newQuery()
 * @method static Builder|PermissionRoles query()
 * @method static Builder|PermissionRoles whereCreatedAt($value)
 * @method static Builder|PermissionRoles wherePermissionId($value)
 * @method static Builder|PermissionRoles whereRoleId($value)
 * @method static Builder|PermissionRoles whereTest($value)
 * @method static Builder|PermissionRoles whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PermissionRoles extends Model
{
    use EloquentGetTableNameTrait;

    protected $table = 'permission_roles';
}
