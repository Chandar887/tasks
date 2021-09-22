<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->create([
            'name' => 'Super Admin',
            'username' => 'sadmin',
            'email' => 'sadmin@sadmin.com',
            'password' => Hash::make($password = 'password'),
            'role' => 'sadmin',
            'enabled' => true,
        ]);
        $user = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make($password = 'password'),
            'role' => 'admin',
            'enabled' => true,
        ]);
    }
}
