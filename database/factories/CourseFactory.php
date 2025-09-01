<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'course_code' => $this->faker->unique()->bothify('CS###'),  // Example: CS101
            'course_name' => $this->faker->words(3, true),              // Example: "Computer Science Basics"
            'description' => $this->faker->sentence(10),
            'department' => $this->faker->randomElement(['Engineering', 'Business', 'Arts', 'Science']),
            'duration_years' => $this->faker->numberBetween(2, 6),
            'is_active' => $this->faker->boolean(90), // 90% chance true
        ];
    }
}
