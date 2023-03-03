<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $role = Role::where('name', 'STUDENT')->first();
        $grade = Grade::inRandomOrder()->first();
        $code = rand(1,1000);
        return [
            'full_name' => fake()->name(),
            'student_code' => 'FPT'.$code,
            'password' => 'bridge123',
            'display_password' => 'bridge123',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => $role->id,
            'grade_id' => $grade->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
