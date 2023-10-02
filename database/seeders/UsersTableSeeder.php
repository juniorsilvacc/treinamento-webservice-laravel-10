<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Cícero Júnior',
            'email' => 'juniorsilvacc@hotmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
