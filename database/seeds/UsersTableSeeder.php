<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Owner',
            'email' => 'Owner@gmail.com',
            'password' => 12345678,
            'role_id' => 1
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 12345678,
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Super-admin',
            'email' => 'Super-admin@gmail.com',
            'password' => 12345678,
            'role_id' => 3
        ]);
        User::create([
            'name' => 'Supervisor',
            'email' => 'Supervisor@gmail.com',
            'password' => 12345678,
            'role_id' => 4
        ]);
        factory(User::class, 500000)->create();
    }

}
