<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRolesTableSeeder::class);
        //
        //$this->call(ItemsCategorySeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(ItemsCategorySeederFactory::class);
        //$this->call(TokensSeeder::class);


        //$this->call(ItemsSeederNew::class);

        //$this->call(ItemsSeeder::class);


        $this->call(OrderContactsSeeder::class);

        //$this->call(OrdersSeeder::class);
        //$this->call(InvoicesSeeder::class);
    }
}
