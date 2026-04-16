<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'bhoidaxesh143@gmail.com'
            ],
            [
                'name' => 'Daxesh Bhoi',
                'password' => bcrypt('Vishwa@!2024'),
                'role_id' => 1
            ]
        );
    }
}
