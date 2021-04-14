<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
               DB::table('users')->insert([
                'fullname' => $faker->name,
                'username' => $faker->userName,
                'email' => $faker->email,
                'user_img' => 'user.png',
                'password' => Hash::make('12345678'),
            ]);
        }
        
    }
}
