<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'admin',
                'role' => 'admin',
                'email' => 'admin@unsri.ac.id',
                'password' => bcrypt('admin'),
            ],
        ];

        foreach ($data as $user) {
            \App\Models\User::create($user);
        }
    }
}
