<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++) {
            User::create([
                'status' => ($i == 0 ? 'teacher' : 'student'),
                'email' => 'kocal' . $i . '@kocal.fr',
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => Hash::make('kocall')
            ]);
        }
    }
}
