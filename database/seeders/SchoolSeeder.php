<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        School::create([
            'name' => 'FPT School',
            // 'school_year_id' => SchoolYear::all()->random()->id,
            'phone_number' => '0987654321',
            "code" => "FPT",
            'name_represent' => 'Nguyễn Trường Xuân',
            'email_in_charge' => 'xghost@gmail.com'
        ]);
        School::create([
            'name' => 'HANOI School',
            // 'school_year_id' => SchoolYear::all()->random()->id,
            'phone_number' => '0989999999',
            "code" => "HNA",
            'name_represent' => 'Nguyễn Trường Xuân',
            'email_in_charge' => 'xghost@gmail.com'
        ]);
    }
}
