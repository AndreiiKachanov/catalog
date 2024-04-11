<?php

namespace Database\Seeders\Prod;

use Database\Seeders\PermissionRolesTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\Prod\SettingsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRolesTableSeeder::class);
        //Retrieves data from hosting
        $this->call(CartTokensSeeder::class);
        $this->call(InvoicesSeeder::class);
        $this->call(ItemsCategorySeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(OrderContactsSeeder::class);
        $this->call(CartItemsSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(SettingsSeeder::class);
//        $this->call(\Database\Seeders\SettingsSeeder::class);
        $this->call(UsersSeeder::class);
        //Retrieves images from hosting
        $this->call(ImagesSeeder::class);
    }
}
