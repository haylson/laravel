<?php

use Illuminate\Database\Seeder;
use CodeDelivery\Models\User;
use CodeDelivery\Models\Client;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(User::class)->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt(123456),
            'role' => 'user',
            'remember_token' => str_random(10),
        ]);

        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt(123456),
            'role' => 'admin',
            'remember_token' => str_random(10),
        ]);

        factory(User::class, 10)->create()->each(function($u) {
           $u->client()->save(factory(Client::class)->make());
        });

        factory(User::class, 3)->create([
            'role' => 'deliveryman',
        ]);
    }
}
