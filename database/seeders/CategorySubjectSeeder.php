<?php

namespace Database\Seeders;

use App\Models\CategorySubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            CategorySubject::create([
                'name' => 'Category '.$i
            ]);
        }
    }
}
