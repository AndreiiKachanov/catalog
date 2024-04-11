<?php

namespace Database\Seeders;

use App\Models\User\PermissionRoles;
use App\Models\User\Role;
use Illuminate\Database\Seeder;
use Exception;

class PermissionRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (PermissionRoles::count() !== 0) {
            throw new Exception(PermissionRoles::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        // Admin
        Role::find(1)->rPermissions()->sync([
            1 => [ 'test' => 'test1' ],
            2 => [ 'test' => 'test2' ]
        ]);
        // Moderator
        Role::find(2)->rPermissions()->sync([
            3 => [ 'test' => 'test3' ],
            4 => [ 'test' => 'test4' ]
        ]);
    }
}
