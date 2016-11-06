<?php

use Illuminate\Database\Seeder;

class OAuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'id' => 'appid01',
                'secret' => 'secret',
                'name' => 'Minha aplicação mobile - ionic',
                'created_at' =>  '06/11/2016',
                'updated_at' =>  '06/11/2016',
            ]
        ]);

    }
}