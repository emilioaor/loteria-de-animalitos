<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrador
        $user = new User();
        $user->name = 'Administrador';
        $user->username = 'admin';
        $user->password = bcrypt('123456');
        $user->level = User::LEVEL_ADMIN;
        $user->print_code = '0000000';
        $user->save();

        // Taquilla inicial
        $user = new User();
        $user->name = 'Taquilla 1';
        $user->username = 'taq1';
        $user->password = bcrypt('123456');
        $user->level = User::LEVEL_USER;
        $user->print_code = '1111111';
        $user->save();
    }
}
