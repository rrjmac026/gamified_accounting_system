<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the student user you created in DatabaseSeeder
        $user = User::where('email', 'jam@jam.com')->first();

        if ($user && $user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'course' => 'BS Accountancy',
                'year_level' => '3rd Year',
                'section' => 'A',
                'total_xp' => 0,
                'current_level' => 1,
                'performance_rating' => 0.00,
            ]);
        }


        $user = User::where('email', 'jam@jam.com')->first();

        if ($user && $user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'course' => 'BS Information Technology',
                'year_level' => '4th Year',
                'section' => 'A',
                'total_xp' => 0,
                'current_level' => 1,
                'performance_rating' => 0.00,
            ]);
        }
    }
}
