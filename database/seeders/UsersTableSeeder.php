<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Exception;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        if (User::count() != 0) {
            throw new Exception(User::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        User::create([
            'email'             => 'test@test.loc',
            'name'              => 'Сергей Бурунов',
            'phone'             => '+79493955454',
            'phone_auth'        => false,
            'phone_verified'    => false,
            'email_verified_at' => Carbon::now(),
            'role_id'           => 1, // Admin
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
            'status'            => User::STATUS_ACTIVE,
            'password'          => Hash::make('qwerty123')
        ]);

//        factory(User::class, 1000)->create();
    }
}
