<?php

namespace Database\Seeders;

use App\Models\GroupType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupType::insert([
            ["name" =>"就労支援事業所"],
            ["name" =>"就労支援移行事業所"],
            ["name" =>"B型事業所"],
            ["name" =>"A型事業所"],
            ["name" =>"一般企業"],
        ]);
    }
}
