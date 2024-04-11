<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Permission;
use Exception;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (Permission::count() != 0) {
            throw new Exception(Permission::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        $permissions = [
            'SHOW_USERS',
            'EDIT_USERS',
            'SHOW_PERMISSIONS',
            'EDIT_PERMISSIONS'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
