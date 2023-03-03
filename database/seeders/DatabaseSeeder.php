<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(SchoolYearSeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(GroupTypeSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(CategorySubjectSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(MentorSeeder::class);
        $this->call(IllustrationsSeeder::class);
        //$this->call(FuriganaSeeder::class);
    }
}
