<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::insert([
            ['name' => 'Programming'],
            ['name' => 'Database'],
            ['name' => 'Networking'],
            ['name' => 'Aptitude'],
        ]);
    }
}
