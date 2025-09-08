<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),  // creates a new User if not provided
            'student_number' => $this->faker->unique()->numerify('S########'),
            'course_id' => Course::factory(), // creates a new Course if not provided
            'year_level' => $this->faker->numberBetween(1, 4), // assuming 1st to 4th year
            'section' => strtoupper($this->faker->randomLetter()) . $this->faker->numberBetween(1, 5),
            'total_xp' => $this->faker->numberBetween(0, 5000),
            'current_level' => $this->faker->numberBetween(1, 50),
            'performance_rating' => $this->faker->randomFloat(2, 0, 100), // 0.00 to 100.00
        ];
    }
}
