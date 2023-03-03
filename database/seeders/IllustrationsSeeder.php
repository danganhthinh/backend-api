<?php

namespace Database\Seeders;

use App\Models\Illustration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IllustrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Illustration::create([
            'name' => 'good',
            'image' => '123.jpg',
            'type' => '1'
        ]);
        Illustration::create([
            'name' => 'EXCELLENT',
            'image' => '123.jpg',
            'type' => '2'
        ]);
    }
}
