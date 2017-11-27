<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 200) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->companyEmail,
                'password' => bcrypt(123456),                
                'remember_token' => $faker->sha1                
            ]);
        }
    }
}
