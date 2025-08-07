<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Jam',
            'email' => 'jam@jam.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::factory()->create([
            'name' => 'Nica',
            'email' => 'nica@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $this->call(StudentSeeder::class);

        
    }
}
