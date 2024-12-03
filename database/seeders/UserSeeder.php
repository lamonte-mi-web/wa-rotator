<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userdata = [
        [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 1,
            'password' => bcrypt('admin')

        ],
        [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'role' => 2,
            'password' => bcrypt('user')

        ],
    ];

    foreach($userdata as $key => $val){
        User::create($val);
    }
    }
}
