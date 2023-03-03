<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 10; $i <= 12; $i++) {
            Grade::create([
                'name' => 'Khối '.$i,
                'code' => 'LP' .$i,
                'school_id' => 1
            ]);
        }
    }
}
