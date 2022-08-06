<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'first_name' => 'Faizan Ahmed',
            'last_name' => 'Raza',
            'username' => 'faizan.raza@saasfa.com',
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(60),
            'status' => User::STATUS['active'],
        ]);

        $admin->assignRole('Admin');
    }
}
