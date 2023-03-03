<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grade = Grade::inRandomOrder()->first();
        $code = rand(1,1000);
        User::factory()->count(50)->create();
        User::create([
            'full_name' => fake()->name(),
            'student_code' => 'FPT'.rand(1,1000),
            'password' => 'bridge1',
            'display_password' => 'bridge1',
            'email_verified_at' => now(),
            'email' =>"chainos@gmail.com",
            'remember_token' => Str::random(10),
            'role_id' => 2,
            'grade_id' => $grade->id,
        ]);
        User::create([
            'full_name' => fake()->name(),
            'student_code' => 'FPT'.rand(1,1000),
            'password' => 'bridge1',
            'display_password' => 'bridge1',
            'email_verified_at' => now(),
            'email' =>"chainos1@gmail.com",
            'remember_token' => Str::random(10),
            'role_id' => 3,
        ]);
    }
}
